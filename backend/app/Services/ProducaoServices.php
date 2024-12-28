<?php
namespace App\Services;

use App\Models\HistoricoProducao;
use App\Models\HistoricosStatusPedido;
use App\Models\ItemPedido;
use App\Models\Producao as Model;
use Exception;

class ProducaoServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function beforeCreateData($data){

        return $data;
    }

    public function show($id) {
        $data = $this->model->find($id);
        
        $data->produto;
        $data->colaborador;
        return response()->json(['data' => $data]);
    }

    public function index($request) {
        $params = $request->all();
        // $data = DB::table('clients as cli')
        // ->select('cli.*')
        // ->join('colaboradors as c', 'c.id', 'cli.vendedor_id')
        // ->when($params, function($query, $params) {
        //     if(isset($params['search'])) {
        //         $query->whereRaw("(
        //             cli.razao_social like '%{$params['search']}%' OR 
        //             cli.nome_fantasia like '%{$params['search']}%' OR
        //             REPLACE(REPLACE(REPLACE(cli.cpf,'.', ''),'-', ''),'/', '') like '%{$params['search']}%' OR
        //             REPLACE(REPLACE(REPLACE(cli.cnpj,'.', ''),'-', ''),'/', '') like '%{$params['search']}%' OR
        //             cli.logradouro like '%{$params['search']}%' OR
        //             cli.bairro like '%{$params['search']}%' OR
        //             cli.cidade like '%{$params['search']}%' OR
        //             cli.cep like '%{$params['search']}%' OR
        //             cli.fone like '%{$params['search']}%' OR
        //             cli.email like '%{$params['search']}%'  
        //         )");
        //         // $query->orWhere('cli.razao_social', 'like', "%{$params['search']}%")
        //         // ->orWhere('cli.nome_fantasia', 'like', "%{$params['search']}%")
        //         // ->orWhere(DB::Raw("REPLACE(REPLACE(REPLACE(cli.cpf,'.', ''),'-', ''),'/', '')"), 'like', "%{$params['search']}%")
        //         // ->orWhere(DB::Raw("REPLACE(REPLACE(REPLACE(cli.cnpj,'.', ''),'-', ''),'/', '')"), 'like', "%{$params['search']}%")
        //         // ->orWhere('cli.logradouro', 'like', "%{$params['search']}%")
        //         // ->orWhere('cli.bairro', 'like', "%{$params['search']}%")
        //         // ->orWhere('cli.cidade', 'like', "%{$params['search']}%")
        //         // ->orWhere('cli.cep', 'like', "%{$params['search']}%")
        //         // ->orWhere('cli.fone', 'like', "%{$params['search']}%")
        //         // ->orWhere('cli.email', 'like', "%{$params['search']}%");
        //     }

        //     if(isset($params['bairro'])) {
        //         $query->where('cli.bairro', 'like', "%{$params['bairro']}%");
        //     }

        //     if(isset($params['cidade'])) {
        //         $query->where('cli.cidade', 'like', "%{$params['cidade']}%");
        //     }

        //     if(isset($params['estado'])) {
        //         $query->where('cli.uf', 'like', "%{$params['estado']}%");
        //     }

        //     if(isset($params['situacao'])) {
        //         $query->where('cli.situacao', $params['situacao']);
        //     }

        //     if(isset($params['vendedor'])) {
        //         $query->where('c.name', 'like', "%{$params['vendedor']}%");
        //     }

        //     return $query;
        // })
        // ->when($params, function($query, $params) {
        //     if(isset($params['vendedor_id'])) {
        //         $query->where('cli.vendedor_id', $params['vendedor_id'])
        //         ->where('c.id', $params['vendedor_id']);
        //     }            
        //     return $query;
        // })
        // ->when($params, function($query, $params) {
            
        //     if(isset($params['has_tabela'])) {
        //         if($params['has_tabela'] == 'true') {
        //             $query->whereNotNull('cli.tabela_id');
        //         } else {
        //             $query->where('cli.tabela_id', 0);
        //         }
        //     }
            
        //     return $query;
        // })
        // ->orderBy('cli.razao_social')
        // ->paginate(10);

        $data = $this->model->paginate(10);
        foreach($data as $item) {
            $item->colaborador;
            $item->colaboradorAux;
            $item->produto;
        }
        return $this->response($data);
    }



    public function afterCreateData($data) {
        // QUANDO O PEDIDO É CRIADO - CRIA UM HISTORICO DE STATUS
        HistoricoProducao::create([
            'producao_id' => $data->id,
            'status' => $data->status,
            'status_reparacao' => $data->status_reparacao,
            'quantidade' =>  $data->quantidade,
            'perda' => isset($this->params['perda']) ? $this->params['perda']: 0,
            'observacao' => $this->params['observacao'],
        ]);
    }

    public function beforeUpdateData($data){
        
        if($data['model']->status === 'finished') {
            throw new Exception("Este pedido já se encontra finalizado e não pode ser editado.");
        }
        
       
        $observacao = '';
        if(isset($data['observacao_status'])) {
            $observacao = $data['observacao_status'];
            $data['observacao'] = $data['observacao_status'];
        }
        HistoricosStatusServices::LancarSatus($data['id'], $data['status'], $observacao);
        
        // echo '<pre>'; print_r($data); die;
        return $data;
    }

    public function beforeDataDelete($data) {
        if($data->status !== 'open') {
            throw new Exception("Não foi possível remover este pedido.");
        }

        
        
        ItemPedido::where('pedido_id', $data->id)->delete();
        HistoricosStatusPedido::where('pedido_id', $data->id)->delete();
    }

}