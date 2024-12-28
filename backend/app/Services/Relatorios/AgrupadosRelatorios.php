<?php
namespace App\Services\Relatorios;

use App\Models\ItemPedido;
use App\Models\Pedido as Model;
use App\Models\Produto;
use App\Services\NegociosServices;
use Illuminate\Support\Facades\DB;

class AgrupadosRelatorios extends RelatoriosServices {
    
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
            ->where('tipo', 'P')
            ->get();

            $pedidosId = [];
            foreach($pedidos as $pedido) {
                $pedidosId[] = $pedido->id;
            }
            $params['pedidos'] = $pedidosId;
        }
        // $data = ItemPedido::whereIn('pedido_id', $params['pedidos'])->get();
        $data = NegociosServices::getItensByOrderIdOrderProduct($params['pedidos']);
        $result = [];
        
        foreach($data as $item) {    
            
            if(!isset($result[$item->produto_id])) {
                $produto = Produto::find($item->produto_id);
                $result[$item->produto_id] = [
                    'produto_id' => $item->produto_id,
                    'produto' => $produto,
                    'quantidade' => $item->quantidade,
                    'valor' => $item->preco * $item->quantidade,
                ];
            } else {
                $result[$item->produto_id]['quantidade'] = $result[$item->produto_id]['quantidade'] + $item->quantidade;
                $result[$item->produto_id]['valor'] += ($item->preco * $item->quantidade);
            }
        }

        $list = [];
        $total = 0;
        
        foreach($result as $item) {
            $total += $item['valor'];
            $list[] = $item;
        }

        foreach($list as $key => $item) {
            $list[$key]['total'] = $total;
        }

        $pedidos = Model::whereIn('id', $params['pedidos'])->get();
        $totalGeral = 0;
        foreach($pedidos as $pedido) {
            $pedido->cliente;
            $total = 0;
            $pedido->itens = NegociosServices::getItensByOrderIdOrderProduct($pedido->id);
            foreach($pedido->itens as $item) {
                $total += NegociosServices::getCalcularDescontoPorPedidoId($pedido->id, $item->id);
            }
            $pedido->total = $total;
            $totalGeral += $total;
        }
        
        $this->name = 'agrupados';
        $this->view = 'relatorios.agrupados';
        $this->data = [
            'list' => $list,
            'pedidos' => $pedidos,
            'total_geral' => $totalGeral,
        ];
    }

}