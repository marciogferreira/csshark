<?php
namespace App\Services\Relatorios;

use App\Models\ItemPedido;
use App\Models\Pedido as Model;
use App\Models\Produto;
use App\Services\NegociosServices;
use Illuminate\Support\Facades\DB;

class PedidoEntregaRelatorios extends RelatoriosServices {
    
    public function config($params) {
        $this->orientation = 'landscape';
        $this->model = new Model();
        if(isset($params['all_pedidos']) && $params['all_pedidos'] == 'true') {
            $pedidos = DB::table('pedidos as p')
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
                        p.codigo like '%{$params['search']}%' 0R
                        c.razao_social like '%{$params['search']}%' OR
                        c.nome_fantasia like '%{$params['search']}%' OR                      
                        c.cnpj like '%{$params['search']}%' OR
                        c.cpf like '%{$params['search']}%' OR
                        c.bairro like '%{$params['search']}%' OR
                        c.cidade like '%{$params['search']}%' OR
                        c.uf like '%{$params['search']}%' OR
                        c.cep like '%{$params['search']}%' OR
                        v.name like '%{$params['search']}%'
                    )");
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
        $data = $this->model->whereIn('id', $params['pedidos'])->get();
        $result = [];
        
        $pesoTotal = 0;
        $valorTotal = 0;
        foreach($data as $item) { 
            $item->cliente;
            $item->vendedor;
            $item->forma_pagamento;
            $item->tipo_pagamento;
            $peso = 0;
            $total = 0;
            $item->itens = NegociosServices::getItensByOrderIdOrderProduct($item->id);
            foreach($item->itens as $item_pedido) {
                $total += NegociosServices::getCalcularDescontoPorPedidoId($item->id, $item_pedido->id);
                $produto = Produto::find($item_pedido->produto_id);
                if($produto) {
                    $peso = $peso + (intval($item_pedido->quantidade) * floatval($produto->peso));
                }
            }
            $valorTotal += $total;
            $item->total = $total;
            $item->pesoTotal = $peso;
            $pesoTotal = $pesoTotal + $peso;
        }
        
        $list = [];
        $total = 0;
        
        // foreach($result as $item) {
        //     $total += $item['valor'];
        //     $list[] = $item;
        // }

        // foreach($list as $key => $item) {
        //     $list[$key]['total'] = $total;
        // }
        
        // echo '<pre>'; print_r($data); die;
        $this->name = 'romaneio';
        $this->view = 'relatorios.romaneio';
        $this->data = [
            'data' => $data,
            'total' => $valorTotal,
            'peso' => $pesoTotal,
        ];
    }

}