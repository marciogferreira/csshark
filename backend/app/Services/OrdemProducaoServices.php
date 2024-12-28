<?php
namespace App\Services;

use App\Models\OrdemProducao as Model;
use App\Models\Pedido;
use App\Models\Produto;
use Carbon\Carbon;
use Exception;

class OrdemProducaoServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->orderBy = 'desc';
        $this->indexOptions = [
            'label' => 'name',
            'value' => 'id',
        ];
    } 

    public function show($id) {
        $data = $this->model->find($id);
        $pedidos = Pedido::where('ordem_producao_correto_id', $data->id)->get();
        $produtos_agrupados = [];

        foreach($pedidos as $pedido) {
            $pedido->cliente;
            $pedido->vendedor;
            $pedido->itens;
            $pedido->data = Carbon::parse($pedido->created_at)->format('d/m/Y H:i:s');

            $total = 0;
            foreach($pedido->itens as $produto) {
                $produto->produto;
                $total = $total + NegociosServices::getCalcularDescontoPorPedidoId($pedido->id, $produto->id);
                // $total = $total + ($produto->quantidade * $produto->preco);
            }

            // $item->total = $total - ($total * $item->desconto / 100);
            $pedido->total = $total;

            foreach($pedido->itens as $item) {

                $produto = Produto::find($item->produto_id);
                
                foreach($produto->itensMontagem as $itemMontagem) {
                    if(isset($produtos_agrupados[$itemMontagem->produto_montagem_id])) {
                        $produtos_agrupados[$itemMontagem->produto_montagem_id]->quantidade += ($item->quantidade * $itemMontagem->quantidade);
                    } else {
                        $produtos_agrupados[$itemMontagem->produto_montagem_id] = $itemMontagem->produto;
                        $produtos_agrupados[$itemMontagem->produto_montagem_id]->quantidade = ($item->quantidade * $itemMontagem->quantidade);
                        $produtos_agrupados[$itemMontagem->produto_montagem_id]['produto'] = $itemMontagem->produtoMontagem;
                    }
                }

                if(count($produto->itensMontagem) == 0) {
                    if(isset($produtos_agrupados[$item->produto_id])) {
                        $produtos_agrupados[$item->produto_id]->quantidade += $item->quantidade;
                    } else {
                        $produtos_agrupados[$item->produto_id] = $item;
                        $produtos_agrupados[$item->produto_id]['produto'] = $item->produto;
                    }
                }
               
            }
        }
        // secho '<pre>'; print_r($produtos_agrupados); die;

        $result_produtos_agrupados = [];
        foreach($produtos_agrupados as $item) {
            $result_produtos_agrupados[] = $item;
        }
        $data->produtos_agrupados = $result_produtos_agrupados;
        $data->pedidos = $pedidos;
        return response()->json(['data' => $data]);
    }   


    public function index($request) {
        $params = $request->all();

        $data = $this->model->when($params, function($query, $params) {
            if(isset($params['search'])) {
                $query->where($this->columnSearch, 'like', "%{$params['search']}%");
            }
            return $query;
        })
        ->when($this->orderBy, function($query, $orderBy) {
            if($orderBy === 'desc') {
                return $query->orderBy('id', 'desc');
            }
        })
        ->paginate(10);

        foreach($data as $item) {
            
            $pedidos = Pedido::where('ordem_producao_correto_id', $item->id)->get();
            $codigos = [];
            foreach($pedidos as $pedido) {
                $codigos[] = $pedido->codigo;
            }
            $item->observacao = 'Ordem de Produçao Nº: '.$item->id. ' - '.$item->observacao;
            $item->pedidos = ''; //implode(',', $pedidos);
            
        }
        return $this->response($data);
    }

    public function afterCreateData($data) {
        foreach($this->params['pedidos'] as $pedido_id) {
            $pedido = Pedido::where('id', $pedido_id)->get()->first();
            if($pedido) {
                if($pedido->ordem_producao_correto_id) {
                    throw new Exception("O pedido de código {$pedido->codigo} já se encontra em uma Ordem de Produção.");
                } else {
                    $pedido->update([
                        'ordem_producao_correto_id' => $data->id,
                        'status' => Pedido::STATUS_PRODUCAO,
                    ]);
                }
            }
            HistoricosStatusServices::LancarSatus($pedido_id, Pedido::STATUS_PRODUCAO, '');
        }
    }

    public function adicionarPedidoOp($request) {
        $params = $request->all();
        $pedido = Pedido::where('codigo', $params['codigo_pedido'])->get()->first();
        if($pedido) {
            $pedido->ordem_producao_correto_id = $params['ordem_producao_id'];
            $pedido->status = Pedido::STATUS_PRODUCAO;
            $pedido->save();
            HistoricosStatusServices::LancarSatus($pedido->id, Pedido::STATUS_PRODUCAO, '');
        } else {
            return response()->json(['message' => 'Código não encontrado para nenhum pedido.']);
        }
        return response()->json(['message' => 'Pedido Adicionado a Ordem de Produção.']);
    }

    public function removePedido($id) {
        try {
            Pedido::find($id)
            ->update([
                'ordem_producao_correto_id' => null,
                'status' => Pedido::STATUS_VIAGEM,
            ]);
            return response()->json(['message' => 'Pedido Removido da Ordem de Produção.']);
        } catch(Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function beforeDataDelete($data) {
        Pedido::where('ordem_producao_correto_id', $data->id)
        ->update([
            'ordem_producao_correto_id' => null
        ]);
    }

}