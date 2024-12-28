<?php
namespace App\Services\Relatorios;

use App\Models\HistoricosStatusPedido;
use App\Models\Pedido as Model;
use App\Services\NegociosServices;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VendasRelatorios extends RelatoriosServices {
    
    public function config($params) {
        $this->model = new Model();

        if(isset($params['all_pedidos']) && $params['all_pedidos'] == 'true') {
            $pedidos = DB::table('pedidos as p')
            ->select('p.*')
            ->orderBy('id', 'desc')
            ->join('clients as c', 'c.id', 'p.cliente_id')
            ->join('colaboradors as v', 'v.id', 'p.vendedor_id')
            ->when($params, function($query, $params){
                if(isset($params['search']) && !empty($params['search'])) {
                    $query->orWhere('p.codigo', 'like', "%{$params['search']}%")
                        ->orWhere('c.razao_social', 'like', "%{$params['search']}%")
                        ->orWhere('c.nome_fantasia', 'like', "%{$params['search']}%")
                        ->orWhere('c.cnpj', 'like', "%{$params['search']}%")
                        ->orWhere('c.cpf', 'like', "%{$params['search']}%")
                        ->orWhere('c.bairro', 'like', "%{$params['search']}%")
                        ->orWhere('c.cidade', 'like', "%{$params['search']}%")
                        ->orWhere('c.uf', 'like', "%{$params['search']}%")
                        ->orWhere('c.cep', 'like', "%{$params['search']}%")
                        ->orWhere('v.name', 'like', "%{$params['search']}%");
                }
                if(isset($params['cnpj']) && !empty($params['cnpj'])) {
                    $query->where(DB::Raw("REPLACE(REPLACE(REPLACE(c.cpf,'.', ''),'-', ''),'/', '')"), 'like', "%{$params['cnpj']}%")
                        ->orWhere(DB::Raw("REPLACE(REPLACE(REPLACE(c.cnpj,'.', ''),'-', ''),'/', '')"), 'like', "%{$params['cnpj']}%");
                }

                if(isset($params['codigo']) && !empty($params['codigo'])) {
                    $query->where('p.codigo', $params['codigo']);
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

                if(isset($params['status_list']) && !empty($params['status_list'])) {
                    $query->whereIn('p.status', $params['status_list']);
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
            ->where('tipo', 'P')
            ->get();

            $pedidosId = [];
            foreach($pedidos as $pedido) {
                $pedidosId[] = $pedido->id;
            }
            $params['pedidos'] = $pedidosId;
        }
        
        $data = Model::whereIn('id', $params['pedidos'])->get();       

        $vendas = [];
        $totalGeral = 0;
        foreach($data as $pedido) {

            $pedido->cliente;
            $vendas[$pedido->vendedor_id]['vendedor'] = $pedido->vendedor;
            $total = 0;
            
            $historico = HistoricosStatusPedido::where('pedido_id', $pedido->id)
            ->where('status', 'finished')->get()->first();
            $pedido->data_entrega = $historico ? Carbon::parse($historico->created_at)->format('d/m/Y') : '';
            $itens = NegociosServices::getItensByOrderIdOrderProduct($pedido->id);
            $pedido->itens = $itens;
            foreach($itens as $item) {
                $total += NegociosServices::getCalcularDescontoPorPedidoId($pedido->id, $item->id);
                
            }

            $totalGeral += $total;
            $pedido->total = $total;
            $vendas[$pedido->vendedor_id]['pedidos'][] = $pedido;

            if(isset($vendas[$pedido->vendedor_id]['total'])){
                $vendas[$pedido->vendedor_id]['total'] += $total;
            } else {
                $vendas[$pedido->vendedor_id]['total'] = $total;
            }            
        }

        $dataInicio = isset($params['data_inicio']) ? Carbon::parse($params['data_inicio'])->format('d/m/Y') : '';
        $dataFim = isset($params['data_fim']) ? Carbon::parse($params['data_fim'])->format('d/m/Y') : '';

        $result = [];
        foreach($vendas as $key => $item) {
            $result[] = $vendas[$key];
        }
        
        $this->name = 'vendas';
        $this->view = 'relatorios.vendas';
        $this->data = [
            'list' => $result,
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim,
            'total_geral' => $totalGeral,
        ];
    }

}