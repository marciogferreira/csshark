<?php
namespace App\Services;

use App\Models\ItemPedido;
use App\Models\OrdemProducao;
use App\Models\Pedido;
use App\Models\ProdutoProducao;
use App\Models\StatusProducao as Model;
use App\Models\StatusProducao;

class StatusProducaoServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->columnSearch = 'nome';
        $this->model = $model;
        $this->indexOptions = [
            'value' => 'id',
            'label' => 'nome',
        ];
    }

    public function options($params) {
        $list = $this->model->all();
        $res = [];
        $value = $this->indexOptions['value'];
        $label = $this->indexOptions['label'];
        
        foreach($list as $item) {
            $res[] = [
                'value' => $item->$value,
                'label' => $item->$label,
                'is_final' => $item->is_final,
            ];
        }
        
        return response(['data' => $res]);
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
            $item->status;
        }
        return $this->response($data);
    }

    public function show($id) {
        $data = $this->model->find($id);
        if($data->is_final) {
            $data->is_final = true;
        } else {
            $data->is_final = false;
        }
        return response()->json(['data' => $data]);
    }

    public function listaPorOrdem($ordem_id){
        $pedidos = Pedido::where('ordem_producao_correto_id', $ordem_id)->get();

        $listaStatus = [];
        foreach($pedidos as $pedido) {
            $list = ItemPedido::where('pedido_id', $pedido->id)->get();
            foreach($list as $item) {
                $item->produto;
                $listaStatus[$item->produto_id][] = $item->toArray();
            }
        }

        $list = [];
        foreach($listaStatus as $produto_id => $pedido) {
            $listStatus = ProdutoProducao::where('produto_id', $produto_id)->get();
            foreach($listStatus as $item) {
                $list[] = [
                    'status_id' => $item->status_producao_id,
                    'status' => $item->statusProducao->toArray(),
                    'items_produtos' => $pedido,
                ];
            }
            
        }

        $result = [];
        foreach($list as $item) {
            if(isset($result[$item['status_id']])) {
                $result[$item['status_id']]['items_produtos'][] = $item['items_produtos'];
            } else {
                $result[$item['status_id']] = $item;
            }
        }

        $list = [];
        foreach($result as $item) {
            $list[] = $item;
        }

        return response()->json(['data' => $list]);
    }
    

}