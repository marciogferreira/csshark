<?php
namespace App\Services;

use App\Models\TabelaPreco as Model;

class TabelaPrecosServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function options($params) {
        $list = $this->model->select('name as label', 'id as value')->get();
        return $this->response(['data' => $list]);
    }

}