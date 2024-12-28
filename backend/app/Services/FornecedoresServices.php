<?php
namespace App\Services;

use App\Models\Fornecedor as Model;
use Exception;

class FornecedoresServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->columnSearch = 'razao_social';
        $this->model = $model;
    }

    public function index($request) {
        $params = $request->all();

        $data = $this->model->when($params, function($query, $params) {
            if(isset($params['search']) && !empty($params['search'])) {
                $query->whereRaw("(razao_social like '%{$params['search']}%'
                    OR nome_fantasia like '%{$params['search']}%'
                    OR cnpj like '%{$params['search']}%'
                    OR bairro like '%{$params['search']}%'
                    OR cidade like '%{$params['search']}%'
                )");
            } 
            return $query;
        })
        ->paginate(10);
        
        foreach($data as $item) {
            $item->vendedor;
        }
        return $this->response($data);
    }


    public function show($id) {
        $data = $this->model->find($id);
        if($data->situacao) {
            $data->situacao = true;
        }else{
            $data->situacao = false;
        }
        return response()->json(['data' => $data]);
    }

    public function options($params) {
        $list = $this->model->select('razao_social as label', 'id as value')->get();
        return $this->response(['data' => $list]);
    }

    public function beforeCreateData($data){
        if(empty($data['cnpj']) && empty($data['cpf'])) {
            throw new Exception("Por favor, informe um CNPJ ou CPF");
        }
        return $data;
    }

}