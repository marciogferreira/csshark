<?php
namespace App\Services;

use App\Models\DependenciasModel;
use App\Models\EstoqueProducao;
use App\Models\LancarProduzido as Model;
use App\Models\Produto;
use App\Models\StatusProducao;
use App\Models\ProdutoMontagem;

class LancarProduzidosServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->indexOptions = [
            'label' => 'name',
            'value' => 'id',
        ];
    }

    public function show($id) {
        $data = $this->model->find($id);
        $data->produto;
        return response()->json(['data' => $data]);
    }

    public function index($request) {
        $params = $request->all();

        $data = $this->model->when($params, function($query, $params) {
            if(isset($params['search'])) {
                $query->where($this->columnSearch, 'like', "%{$params['search']}%");
            }
            return $query;
        })->orderBy('id', 'desc')->paginate(10);

        foreach($data as $item) {
            $item->produto;
            $item->colaborador;
            $item->colaboradorAux;
            $item->statusProducao;
        }

        return $this->response($data);
    }

    public function removeQuantidadeStatusAnterior($produto_id, $status_producao_id, $quantidade) {
        $statusProducao = StatusProducao::find($status_producao_id);
        
        if($statusProducao) {
            if($statusProducao->status_producao_id) {

                $estoqueProducao = EstoqueProducao::where('produto_id', $produto_id)
                ->where('status_producao_id', $statusProducao->status_producao_id)
                ->get()->first();
                
                if($estoqueProducao) {
                    $estoqueProducao->update([
                        'quantidade' => $estoqueProducao->quantidade - $quantidade,
                    ]);
                }
                // echo '<pre>'; print_r($estoqueProducao); die;
            }
        }
    }

    public function beforeCreateData($params) {

        $estoqueProducao = EstoqueProducao::where('produto_id', $params['produto_id'])
            ->where('status_producao_id', $params['status_producao_id'])
            ->when($params, function($query, $params) {
                if(isset($params['cor_id']) && !empty($params['cor_id'])) {
                    $query->where('cor_id', $params['cor_id']);
                }
                return $query;
            })
            ->get()
            ->first();
        
        $this->removeQuantidadeStatusAnterior($params['produto_id'], $params['status_producao_id'], $params['quantidade']);

        $statusProducao = StatusProducao::find($params['status_producao_id']);

        if($statusProducao->is_final) {
            
            $produto = Produto::find($params['produto_id']);
            
            $produtosMontagem = ProdutoMontagem::where('produto_id', $params['produto_id'])->get();
            foreach($produtosMontagem as $item) {
                $this->removeQuantidadeStatusAnterior($item['produto_montagem_id'], $params['status_producao_id'], $params['quantidade'] * $item->quantidade);    
            }

            $produtoDependencia = DependenciasModel::where('produto_id', $params['produto_id'])->get();
            foreach($produtoDependencia as $item) {
                $this->removeQuantidadeStatusAnterior($item->dependencia_id, $params['status_producao_id'], $params['quantidade'] * $item->quantidade);    
            }

            $produto->quantidade += $params['quantidade'];
            $produto->save();

        } else {
            if($estoqueProducao) {
                $estoqueProducao->quantidade += $params['quantidade'];
                $estoqueProducao->save();
            } else {
                EstoqueProducao::create([
                    'status_producao_id' => $params['status_producao_id'],
                    'produto_id' => $params['produto_id'],
                    'quantidade' => $params['quantidade'],
                    'cor_id' => isset($params['cor_id']) ? $params['cor_id'] : 0
                ]);
            }

            $produto = Produto::find($params['produto_id']);
            if($produto) {
                $produto->quantidade += $params['quantidade'];
                $produto->save();
            }
        }
       
        return $params;
    }

    public function beforeDataDelete($data) {
        $produto = Produto::find($data->produto_id);
        if($produto) {
            $produto->quantidade -= $data->quantidade;
            $produto->save();
        }
    }

}