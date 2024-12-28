<?php
namespace App\Services;

use App\Models\ItensTiposPagamentos as Model;
use App\Models\Pedido;
use Exception;

class ItensTiposPagamentosServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function index($request) {
        $params = $request->all();
        if(isset($params['search'])) {
            $data = $this->model->where('name', 'like', "%{$params['search']}%")
            ->where('forma_pagamento_id', $params['forma_pagamento_id'])
            ->get();
        } else {
            $data = $this->model->where('forma_pagamento_id', $params['forma_pagamento_id'])
            ->get();
        }
        foreach($data as $item) {
            $item->forma_pagamento = $item->formaPagamento();
        }
        return $this->response(['data' => $data]);
    }

    public function options($params) {
        $params = request()->input();
        $list = $this->model
            ->where('forma_pagamento_id', $params['forma_pagamento_id'])
            ->select('id as value', 'descricao as label')->get();
        return $this->response(['data' => $list]);
    }

    public function beforeDataDelete($data) {
        
        $pedido = Pedido::where('item_tipo_pagamento_id', $data->id)->first();

        if($pedido) {
            throw new Exception("O pedido de código: ".$pedido->codigo. " está utilizando essa forma de pagamento. Por favor, altere o pedido para depois excluir.");
        }
    }

}