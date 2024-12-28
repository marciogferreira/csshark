<?php
namespace App\Services;

use App\Models\Client as Model;
use App\Models\Colaborador;
use Exception;
use Illuminate\Support\Facades\DB;

class ClientsServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function options($params) {
        $list = $this->model->select(DB::Raw("CONCAT(razao_social, ' - ', cnpj) as label"), "id as value")
        ->when($params, function($query, $params) {
            if(isset($params['vendedor_id'])) {
                $query->where('vendedor_id', $params['vendedor_id']);
            }
            if(isset($params['ativo'])) {
                $query->where('ativo', $params['ativo']);
            }
            return $query;
        })
        ->get();
        return $this->response(['data' => $list]);
    }
    
    public function index($request) {
        $params = $request->all();
        $data = DB::table('clients as cli')
        ->select('cli.*')
        ->join('colaboradors as c', 'c.id', 'cli.vendedor_id')
        ->when($params, function($query, $params) {
            if(isset($params['search'])) {
                $query->whereRaw("(
                    cli.razao_social like '%{$params['search']}%' OR 
                    cli.nome_fantasia like '%{$params['search']}%' OR
                    REPLACE(REPLACE(REPLACE(cli.cpf,'.', ''),'-', ''),'/', '') like '%{$params['search']}%' OR
                    REPLACE(REPLACE(REPLACE(cli.cnpj,'.', ''),'-', ''),'/', '') like '%{$params['search']}%' OR
                    cli.logradouro like '%{$params['search']}%' OR
                    cli.bairro like '%{$params['search']}%' OR
                    cli.cidade like '%{$params['search']}%' OR
                    cli.cep like '%{$params['search']}%' OR
                    cli.fone like '%{$params['search']}%' OR
                    cli.email like '%{$params['search']}%'  
                )");
                // $query->orWhere('cli.razao_social', 'like', "%{$params['search']}%")
                // ->orWhere('cli.nome_fantasia', 'like', "%{$params['search']}%")
                // ->orWhere(DB::Raw("REPLACE(REPLACE(REPLACE(cli.cpf,'.', ''),'-', ''),'/', '')"), 'like', "%{$params['search']}%")
                // ->orWhere(DB::Raw("REPLACE(REPLACE(REPLACE(cli.cnpj,'.', ''),'-', ''),'/', '')"), 'like', "%{$params['search']}%")
                // ->orWhere('cli.logradouro', 'like', "%{$params['search']}%")
                // ->orWhere('cli.bairro', 'like', "%{$params['search']}%")
                // ->orWhere('cli.cidade', 'like', "%{$params['search']}%")
                // ->orWhere('cli.cep', 'like', "%{$params['search']}%")
                // ->orWhere('cli.fone', 'like', "%{$params['search']}%")
                // ->orWhere('cli.email', 'like', "%{$params['search']}%");
            }

            if(isset($params['bairro'])) {
                $query->where('cli.bairro', 'like', "%{$params['bairro']}%");
            }

            if(isset($params['cidade'])) {
                $query->where('cli.cidade', 'like', "%{$params['cidade']}%");
            }

            if(isset($params['estado'])) {
                $query->where('cli.uf', 'like', "%{$params['estado']}%");
            }

            if(isset($params['situacao'])) {
                $query->where('cli.situacao', $params['situacao']);
            }

            if(isset($params['vendedor'])) {
                $query->where('c.name', 'like', "%{$params['vendedor']}%");
            }

            return $query;
        })
        ->when($params, function($query, $params) {
            if(isset($params['vendedor_id'])) {
                $query->where('cli.vendedor_id', $params['vendedor_id'])
                ->where('c.id', $params['vendedor_id']);
            }
            if(isset($params['ativo'])) {
                $query->where('cli.ativo', $params['ativo']);
            }

            if(isset($params['rejeitado'])) {
                $query->where('cli.rejeitado', $params['rejeitado']);
            }
            return $query;
        })
        ->when($params, function($query, $params) {
            
            if(isset($params['has_tabela'])) {
                if($params['has_tabela'] == 'true') {
                    $query->whereNotNull('cli.tabela_id');
                } else {
                    $query->where('cli.tabela_id', 0);
                }
            }
            
            return $query;
        })
        ->orderBy('cli.razao_social')
        ->paginate(10);

        foreach($data as $item) {
            $item->vendedor = Colaborador::find($item->vendedor_id);
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
        return $data;
    }


}