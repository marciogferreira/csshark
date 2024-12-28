<?php
namespace App\Services\Relatorios;

use App\Models\EstoqueProducao;
use App\Models\OrdemProducao;
use App\Models\Pedido;
use App\Models\Produto as Model;
use App\Models\ProdutoMontagem;

class EstoquePedidoRelatorios extends RelatoriosServices {
    
    public function config($params) {

        $this->orientation = 'landscape';
        $this->model = new Model();

        $pedidos = Pedido::whereIn('ordem_producao_correto_id', $params['pedidos'])->get();
        $produtosPedidos = [];
        foreach($pedidos as $pedido) {
            foreach($pedido->itens as $item) {

                $produtosMontagens = ProdutoMontagem::where('produto_id', $item->produto->id)->get();
                foreach($produtosMontagens as $produtoMontagem) {
                    if(isset($produtosPedidos[$produtoMontagem->produto_montagem_id])) {
                        $produtosPedidos[$produtoMontagem->produto_montagem_id]->quantidade += ($item->quantidade * $produtoMontagem->quantidade);
                    } else {
                        $produtosPedidos[$produtoMontagem->produto_montagem_id] = $produtoMontagem->produtoMontagem;
                        $produtosPedidos[$produtoMontagem->produto_montagem_id]->quantidade = ($item->quantidade * $produtoMontagem->quantidade);
                    }
                }
            }
        }
        

        $produtosEstoque = EstoqueProducao::where('quantidade', '>', 0)->get();
        foreach($produtosEstoque as $produto) {

            if(isset($produtosPedidos[$produto->produto_id])) {
                if(!isset($produtosPedidos[$produto->produto_id]->quantidade_estoque)) {
                    $produtosPedidos[$produto->produto_id]->quantidade_estoque = 0;
                }
                $produtosPedidos[$produto->produto_id]->quantidade_estoque += $produto->quantidade;
            }
        }
        
        $data = [
            'produtos_pedidos' => $produtosPedidos
        ];
        
        $this->name = 'estoque_pedido';
        $this->view = 'relatorios.estoque_pedido';
        $this->data = $produtosPedidos;
    }

}