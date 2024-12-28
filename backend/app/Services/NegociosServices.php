<?php
namespace App\Services;

use App\Models\ItemPedido;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;

class NegociosServices {

    public static function getCalcularDescontoPorPedidoId($id, $item_id) {
        $pedido = Pedido::find($id);
        $item_pedido = ItemPedido::find($item_id);
        
        $preco = $item_pedido->preco;
        $quantidade = $item_pedido->quantidade;

        $tipoDesconto = 'porcentagem';
        $descontoValor = 0;
        $descontoPorcentagem = 0;

        if(!empty($pedido->desconto)) {
            $descontoPorcentagem = $pedido->desconto;
        } else {
            if($pedido->tipo_pagamento_id) {
                if(!empty($pedido->tipo_pagamento->preco_desconto)) {
                    $descontoValor = $pedido->tipo_pagamento->preco_desconto;
                    $tipoDesconto = 'valor';
                } else {
                    $descontoPorcentagem = $pedido->tipo_pagamento->desconto;
                }
            }   
        }

        $total = $preco * $quantidade;
        // echo '<pre>'; print_r(floatval($total ? $total : 0) - (floatval($total) * floatval($descontoPorcentagem) / 100)); die;
        if($tipoDesconto == 'porcentagem') {
            return  floatval($total ? $total : 0) - (floatval($total) * floatval($descontoPorcentagem) / 100);
        } else {
            return  (floatval($total) - floatval($descontoValor));
        }
        
    }

    public static function getItensByOrderIdOrderProduct($pedido_id) {
        
        return DB::table('itens_pedidos as ip')
            ->select('ip.*')
            ->join('produtos as p', 'p.id', 'ip.produto_id')
            ->when($pedido_id, function($query, $pedido_id) {
                if(is_array($pedido_id)) {
                    $query->whereIn('ip.pedido_id', $pedido_id);
                } else {
                    $query->where('ip.pedido_id', $pedido_id);
                }

                return $query;
            })
            ->orderBy('p.titulo', 'asc')
            ->get();
    }

}