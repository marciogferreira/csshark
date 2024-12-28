<?php
namespace App\Services;

use App\Models\Client;
use App\Models\Colaborador;
use App\Models\HistoricosStatusPedido;
use App\Models\ItemPedido;
use App\Models\Pedido as Model;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Visita;

class PedidosVendedoresServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function show($id) {
        $data = $this->model->find($id);
        if(!empty($data->tabela_id)) {
            $data->tabela;
        }
        if(!empty($data->forma_pagamento_id)) {
            $data->forma_pagamento;
        }
        if(!empty($data->item_tipo_pagamento_id)) {
            $data->tipo_pagamento;
        }
        $data->cliente;
        $data->vendedor;
        $data->itens;
        $data->today = Carbon::now()->format('d/m/Y H:i:s');

        $total = 0;
        foreach($data->itens as $item) {
            $item->produto;
            $total = $total + ($item->quantidade * $item->preco);
        }
        $data->total = $total;

        return response()->json(['data' => $data]);
    }
    
    public function index($request) {
        $params = $request->all();
                    
            DB::enableQueryLog();
            $data = DB::table('pedidos as p')
                ->select('p.*')
                ->orderBy('id', 'desc')
                ->join('clients as c', 'c.id', 'p.cliente_id')
                ->join('colaboradors as v', 'v.id', 'p.vendedor_id')
                ->when($params, function($query, $params){
                    if(isset($params['search']) && !empty($params['search'])) {
                        // $query->orWhere('p.codigo', 'like', "%{$params['search']}%")
                        //     ->orWhere('c.razao_social', 'like', "%{$params['search']}%")
                        //     ->orWhere('c.nome_fantasia', 'like', "%{$params['search']}%")
                        //     ->orWhere('c.cnpj', 'like', "%{$params['search']}%")
                        //     ->orWhere('c.cpf', 'like', "%{$params['search']}%")
                        //     ->orWhere('c.bairro', 'like', "%{$params['search']}%")
                        //     ->orWhere('c.cidade', 'like', "%{$params['search']}%")
                        //     ->orWhere('c.uf', 'like', "%{$params['search']}%")
                        //     ->orWhere('c.cep', 'like', "%{$params['search']}%")
                        //     ->orWhere('v.name', 'like', "%{$params['search']}%");
                    $query->whereRaw("(
                            p.codigo like '%{$params['search']}%' or 
                            c.razao_social like '%{$params['search']}%' or 
                            c.nome_fantasia like '%{$params['search']}%' or                       
                            c.cnpj like '%{$params['search']}%' or 
                            c.cpf like '%{$params['search']}%' or 
                            c.bairro like '%{$params['search']}%' or 
                            c.cidade like '%{$params['search']}%' or 
                            c.uf like '%{$params['search']}%' or 
                            c.cep like '%{$params['search']}%' or 
                            v.name like '%{$params['search']}%'
                        )");
                    }
                    if(isset($params['cnpj']) && !empty($params['cnpj'])) {
                        $query->where(DB::Raw("REPLACE(REPLACE(REPLACE(c.cpf,'.', ''),'-', ''),'/', '')"), 'like', "%{$params['cnpj']}%")
                            ->orWhere(DB::Raw("REPLACE(REPLACE(REPLACE(c.cnpj,'.', ''),'-', ''),'/', '')"), 'like', "%{$params['cnpj']}%");
                    }
    
                    if(isset($params['codigo']) && !empty($params['codigo'])) {
                        $query->where('p.codigo', 'like', "%{$params['codigo']}%");
                    }
    
                    if(isset($params['estado']) && !empty($params['estado'])) {
                        $query->where('c.uf', 'like', "%{$params['estado']}%");
                    }
    
                    if(isset($params['vendedor']) && !empty($params['vendedor'])) {
                        $query->where('v.name', 'like', "%{$params['vendedor']}%");
                    }
                    
                    if(isset($params['bairro']) && !empty($params['bairro'])) {
                        $query->where('c.bairro', 'like', "%{$params['bairro']}%");
                    }
    
                    if(isset($params['cidade']) && !empty($params['cidade'])) {
                        $query->where('c.cidade', 'like', "%{$params['cidade']}%");
                    }
    
                    if(isset($params['status']) && !empty($params['status'])) {
                        $query->where('p.status', $params['status']);
                    }
    
                    if(isset($params['status']) && !empty($params['status'])) {
                        $query->where('p.status', $params['status']);
                    }
    
                    if(isset($params['periodo']) && !empty($params['periodo'])) {
                        if($params['periodo'] == 'today') {
                            $query->where(DB::raw("DATE_FORMAT(p.created_at, '%Y-%m-%d')"), Carbon::now()->format('Y-m-d'));
                        }
                        if($params['periodo'] == 'yesterday') {
                            $query->where(DB::raw("DATE_FORMAT(p.created_at, '%Y-%m-%d')"), Carbon::now()->subDay()->format('Y-m-d'));
                        }
                        if($params['periodo'] == 'month') {
                            $query->where(DB::raw("DATE_FORMAT(p.created_at, '%Y-%m')"), Carbon::now()->format('Y-m'));
                        }
                    }
    
                    if(isset($params['data_inicio']) && !empty($params['data_inicio'])) {
                        if(isset($params['data_inicio']) && !isset($params['data_fim'])) {
                            $query->where(DB::raw("DATE_FORMAT(p.created_at, '%Y-%m-%d')"), '>=', $params['data_inicio']);
                        }
                        if(isset($params['data_inicio']) && isset($params['data_fim'])) {
                            $query->whereBetween(DB::raw("DATE_FORMAT(p.created_at, '%Y-%m-%d')"), [$params['data_inicio'], $params['data_fim']]);
                        }
                    }
    
                    if(isset($params['vendedor_id']) && !empty($params['vendedor_id'])) {
                        $query->where('p.vendedor_id', $params['vendedor_id']);
                    }
                    
                    return $query;
                })
            ->where('p.vendedor_id', $request->user()->colaborador_id)
            ->paginate(10);
        // dd(DB::getQueryLog());

        foreach($data as $item) {
            $item->vendedor = Colaborador::find($request->user()->colaborador_id);
            $item->cliente = Client::find($item->cliente_id);
            $item->data = Carbon::parse($item->created_at)->format('d/m/Y H:i:s');

            $item->itens = ItemPedido::where('pedido_id', $item->id)->get();
            $total = 0;
            
            foreach($item->itens as $produto) {
                $produto->produto;
                $total = $total + ($produto->quantidade * $produto->preco);
            }

            $item->total = $total - ($total * $item->desconto / 100);
        }
        return $this->response($data);
    }

    public function sanitizeDataCreate($data) {
        $data->cliente;
        return $data;
    }

    public function beforeCreateData($data){

        $maxCodigo = $this->model->selectRaw("max(codigo) as max")
            ->get()->first();
        
        if($maxCodigo) {
            $codigoMax = (int) $maxCodigo->max;
            $codigoMax = $codigoMax + 1;
            $codigoMax = str_pad($codigoMax, 4, '0', STR_PAD_LEFT);
            $data['codigo'] = $codigoMax;
        } else {
            $data['codigo'] = '0001';
        }

        $cliente = Client::find($data['cliente_id']);
        $data['tabela_id'] = $cliente->tabela_id;
        $data['vendedor_id'] = $cliente->vendedor_id;
        
        if(isset($data['forma_pagamento_id']) && !empty($data['forma_pagamento_id'])) {
            $data['status'] = Model::STATUS_AGUARDANDO;
        } else {
            $data['status'] = Model::STATUS_ABERTO;
        }
        
        return $data;
    }

    public function afterCreateData($data) {
        if($data->status == Model::STATUS_ABERTO) {
            HistoricosStatusServices::LancarSatus($data->id, $data->status, '');
        }

        Visita::create([

            'cliente_id' => $data->cliente_id,
            'vendedor_id' => $data->vendedor_id,
            'pedido_id' => $data->id,
            
            'data_visita' => Carbon::now()->setTimezone('America/Sao_Paulo'),
            'hora_visita' => Carbon::now()->setTimezone('America/Sao_Paulo')->format('H:i:s'),

            'lat_check_in' => $this->params['lat'],
            'lng_check_in' => $this->params['lng'],
            'hora_check_in' => Carbon::now()->setTimezone('America/Sao_Paulo')->format('H:i:s'),

            'lat_check_out' => '',
            'lng_check_out' => '',
            'hora_check_out' => '',

            'observacao' => '',
        ]);
    }

    public function beforeUpdateData($data){
        
        // if(isset($data['status']) && $data['status'] == '5') {
        //     $data['status'] = Model::STATUS_APROVADO;
        // } else {
        //     if(isset($data['status']) && $data['status'] == '3') {
        //         $data['status'] = Model::STATUS_FINALIZADO;
        //     } else {
        //         if(isset($params['forma_pagamento_id']) && !empty($params['forma_pagamento_id'])) {
        //             $data['status'] = Model::STATUS_AGUARDANDO;
        //         } else {
        //             $data['status'] = Model::STATUS_ABERTO;
        //         }
        //     }
        // }
        if(isset($data['forma_pagamento_id']) && !empty($data['forma_pagamento_id'])) {
            $data['status'] = Model::STATUS_AGUARDANDO;
        }

        if(isset($data['observacao_status'])) {
            HistoricosStatusServices::LancarSatus($data['id'], $data['status'], $data['observacao_status']);
        }
        
        // echo '<pre>'; print_r($data); die;
        return $data;
    }

    public function afterUpdateData($data) {

        
        if(isset($this->params['lat']) && !empty($this->params['lat'])) {
            $visita = Visita::where('pedido_id', $data->id)
            ->whereNotNull('lat_check_in')
            ->whereNull('lat_check_out')
            ->get()->first();
            // echo '<pre>'; print_r($visita); die;
            
            if($visita) {
                $visita->update([
                    'lat_check_out' => $this->params['lat'],
                    'lng_check_out' => $this->params['lng'],
                    'hora_check_out' => Carbon::now()->setTimezone('America/Sao_Paulo')->format('h:i:s'),
                    'observacao' => $this->params['observacao_visita'],
                ]);
            }
        }

        if(isset($this->params['data_visita']) && !empty($this->params['data_visita'])) {
            Visita::create([
                'cliente_id' => $data->cliente_id,
                'vendedor_id' => $data->vendedor_id,
                'pedido_id' => $data->id,
                
                'data_visita' => $this->params['data_visita'],
                'hora_visita' => $this->params['hora_visita'],
    
                'observacao' => $this->params['observacao_visita'],
            ]); 
        }
        
    }

    public function beforeDataDelete($data) {
        if($data->status !== 'open') {
            throw new Exception("Não foi possível remover este pedido.");
        }
        ItemPedido::where('pedido_id', $data->id)->delete();
        HistoricosStatusPedido::where('pedido_id', $data->id)->delete();
    }

}