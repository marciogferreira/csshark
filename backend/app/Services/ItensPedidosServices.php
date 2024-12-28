<?php
namespace App\Services;

use App\Models\DependenciasModel;
use App\Models\ItemPedido as Model;
use App\Models\ItemPedido;
use App\Models\Pedido;
use App\Models\Produto;
use Exception;
use Illuminate\Support\Facades\DB;

class ItensPedidosServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    
    public function index($request) {
        $params = $request->all();
        $data = DB::table('itens_pedidos as ip')
            ->select('ip.*')
            ->join('produtos as p', 'p.id', 'ip.produto_id')
            ->when($params, function($query, $params) {
                if(isset($params['pedido_id']) && !empty($params['pedido_id'])) {
                    $query->where('ip.pedido_id', $params['pedido_id']);
                }
                return $query;
            })
            ->orderBy('p.titulo', 'asc')
            ->paginate(100);

        $total = 0;
        foreach($data as $item) {
            $item->produto = Produto::find($item->produto_id);

            $item->exibir = true;
            $isDependencia = DependenciasModel::where('dependencia_id', $item->produto_id)->first();
            if($isDependencia) {
                $item->exibir = false;
            }

            $total = $total + ($item->quantidade * $item->preco);
            $item->total = $total;
        }
        
        return $this->response($data);
    }
    
    public function listAgrupados($params) {
        $list = Model::whereIn('pedido_id', $params['pedidos'])->get();
        $result = [];
        foreach($list as $item) {
            if(!isset($result[$item->produto_id])) {
                $produto = Produto::find($item->produto_id);
                $result[$item->produto_id] = [
                    'produto_id' => $item->produto_id,
                    'produto' => $produto,
                    'quantidade' => $item->quantidade,
                    'valor' => $item->preco * $item->quantidade,
                ];
            } else {
                $result[$item->produto_id]['quantidade'] = $result[$item->produto_id]['quantidade'] + $item->quantidade;
                $result[$item->produto_id]['valor'] += ($item->preco * $item->quantidade);
            }
        }
        $list = [];
        $total = 0;
        foreach($result as $item) {
            $total += $item['valor'];
            $list[] = $item;
        }

        foreach($list as $key => $item) {
            $list[$key]['total'] = $total;
        }

        return $this->response(['data' => $list]);
    }

    public function beforeCreateData($data){
        $produto = Produto::find($data['produto_id']);
        $pedido = Pedido::find($data['pedido_id']);
        
        $dependencias = DependenciasModel::where('produto_id', $produto->id)->get();
        foreach($dependencias as $item) {
            $dependencia = Produto::find($item->dependencia_id);
            $preco = $dependencia->valor + ($dependencia->valor * $pedido->cliente->tabela->porcentagem / 100);
            
            ItemPedido::create([
                'preco' => $preco,
                'quantidade' => $data['quantidade'] * $item->quantidade,
                'desconto' => 0,
                'pedido_id' => $pedido->id,
                'produto_id' => $item->dependencia_id,
            ]);

        }

        // $data['preco'] = $produto->valor;
        $data['preco'] = $produto->valor + ($produto->valor * $pedido->cliente->tabela->porcentagem / 100);
        if(!isset($data['desconto']) || empty($data['desconto'])) {
            $data['desconto'] = 0;
        }
        return $data;
    }

    public function beforeDataDelete($data) {
        $dependencias = DependenciasModel::where('produto_id', $data->produto_id)->get();
        foreach($dependencias as $item) {
            ItemPedido::where([
                'pedido_id' => $data->pedido_id,
                'produto_id' => $item->dependencia_id,
            ])->delete();
        }
    }
}