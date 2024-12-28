<?php
namespace App\Services;

use App\Models\Client as Model;
use Exception;

class ClientsVendedorServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function index($request) {
        $params = $request->all();
        
        if(isset($params['search'])) {
            $data = $this->model
            //->where('title', 'like', "%{$params['search']}%")
            ->when($params, function($query, $params) {
                if(isset($params['search'])) {
                    $query->whereRaw("(
                        razao_social like '%{$params['search']}%' OR 
                        nome_fantasia like '%{$params['search']}%' OR
                        REPLACE(REPLACE(REPLACE(cpf,'.', ''),'-', ''),'/', '') like '%{$params['search']}%' OR
                        REPLACE(REPLACE(REPLACE(cnpj,'.', ''),'-', ''),'/', '') like '%{$params['search']}%' OR
                        logradouro like '%{$params['search']}%' OR
                        bairro like '%{$params['search']}%' OR
                        cidade like '%{$params['search']}%' OR
                        cep like '%{$params['search']}%' OR
                        fone like '%{$params['search']}%' OR
                        email like '%{$params['search']}%'  
                    )");
                }
            })
            ->where('vendedor_id', $request->user()->colaborador_id)
            ->where('ativo', 1)
            ->where('rejeitado', Model::STATUS_ACEITO)
            ->paginate(10);
        } else {
            $data = $this->model->where('vendedor_id', $request->user()->colaborador_id)
                ->where('rejeitado', Model::STATUS_ACEITO)
                ->paginate(10);
        }
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

    public function beforeCreateData($data){
        if(empty($data['cnpj']) && empty($data['cpf'])) {
            throw new Exception("Por favor, informe um CNPJ ou CPF");
        }
        
        if(!$this->user->colaborador_id) {
            throw new Exception("Usuário não vinculado a nenhum Colaborador.");
        }

        $result = $this->model->when($data, function($query, $data) {

            if(isset($data['cpf']) && !empty($data['cpf'])) {
                $query->orWhere('cpf', $data['cpf']);
            }
            if(isset($data['cnpj']) && !empty($data['cnpj'])) {
                $query->orWhere('cnpj', $data['cnpj']);
            }           

            return $query;
        })
        ->get()
        ->first();
        
        if($result) {
            throw new Exception("Não foi possível cadastrar cliente, pois já existe um cliente com o mesmo CNPJ/CPF cadastrado.");
        }

        $data['vendedor_id'] = $this->user->colaborador_id;
        return $data;
    }


}