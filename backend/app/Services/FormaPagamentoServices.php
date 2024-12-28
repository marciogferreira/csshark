<?php
namespace App\Services;

use App\Models\FormaPagamento as Model;

class FormaPagamentoServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function index($request) {
        $params = $request->all();
        if(isset($params['search'])) {
            $data = $this->model->where('name', 'like', "%{$params['search']}%")->paginate(10);
        } else {
            $data = $this->model->paginate(10);
        }
        foreach($data as $item) {
           $item->tipo_pagamento_f = $item->getTipoPagamento();
        }
        return $this->response($data);
    }

    public function options($params) {
        $list = $this->model->select('id as value', 'name as label')->get();
        foreach($list as $item) {
            $item->itensTiposPagamentos;
        }
        return $this->response(['data' => $list]);
    }

}