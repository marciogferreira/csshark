<?php
namespace App\Services;

use App\Models\DependenciasModel as Model;
use Exception;

class DependenciasServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function listProdutos($id) {
        $list = $this->model->where('produto_id', $id)->get();
        foreach($list as $item) {
            $item->produto;
            $item->produtoDependencia;
        }
        return $this->response(['data' => $list]);
    }

    public function beforeCreateData($params) {

        if($params['produto_id'] == $params['dependencia_id']) {
            throw new Exception("Não é possível adicionar o mesmo item produto ao produto.");
        }

        $data = $this->model->where('produto_id', $params['produto_id'])
        ->where('dependencia_id', $params['dependencia_id'])->first();

        if($data) {
            throw new Exception("Este Item já foi adicionado a este produto.");
        }

        return $params;
    }


}