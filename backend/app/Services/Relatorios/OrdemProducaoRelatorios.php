<?php
namespace App\Services\Relatorios;

use App\Models\EstoqueProducao;
use App\Models\OrdemProducao as Model;
use App\Models\Pedido;
use App\Models\Produto;
use App\Services\NegociosServices;
use Carbon\Carbon;

class OrdemProducaoRelatorios extends RelatoriosServices {
    
    public function config($params) {
        $this->model = new Model();

        $data = $this->model->find($params['id']);
        $produtos_agrupados = [];
        if($data) {
            $pedidos = Pedido::where('ordem_producao_correto_id', $data->id)->get();
    
            foreach($pedidos as $pedido) {
                $pedido->cliente;
                $pedido->vendedor;
                $pedido->itens;
                $pedido->data = Carbon::parse($pedido->created_at)->format('d/m/Y H:i:s');
    
                $total = 0;
                foreach($pedido->itens as $produto) {
                    $produto->produto;
                    $total = $total + NegociosServices::getCalcularDescontoPorPedidoId($pedido->id, $produto->id);
                    // $total = $total + ($produto->quantidade * $produto->preco);
                }
    
                // $item->total = $total - ($total * $item->desconto / 100);
                $pedido->total = $total;
    
                foreach($pedido->itens as $item) {
    
                    $produto = Produto::find($item->produto_id);
                    
                    foreach($produto->itensMontagem as $itemMontagem) {
                        if(isset($produtos_agrupados[$itemMontagem->produto_montagem_id])) {
                            $produtos_agrupados[$itemMontagem->produto_montagem_id]->quantidade += ($item->quantidade * $itemMontagem->quantidade);
                        } else {
                            $produtos_agrupados[$itemMontagem->produto_montagem_id] = $itemMontagem->produto;
                            $produtos_agrupados[$itemMontagem->produto_montagem_id]->quantidade = ($item->quantidade * $itemMontagem->quantidade);
                            $produtos_agrupados[$itemMontagem->produto_montagem_id]['produto'] = $itemMontagem->produtoMontagem;
                        }
                    }
    
                    if(count($produto->itensMontagem) == 0) {
                        if(isset($produtos_agrupados[$item->produto_id])) {
                            $produtos_agrupados[$item->produto_id]->quantidade += $item->quantidade;
                        } else {
                            $produtos_agrupados[$item->produto_id] = $item;
                            $produtos_agrupados[$item->produto_id]['produto'] = $item->produto;
                        }
                    }
                   
                }
            }
        }
       
        // secho '<pre>'; print_r($produtos_agrupados); die;

        $result_produtos_agrupados = [];
        foreach($produtos_agrupados as $item) {
            $result_produtos_agrupados[] = $item;
        }
        if($data) {
            $data->produtos_agrupados = $result_produtos_agrupados;
            $data->pedidos = $pedidos;
        }
        
        
        $this->name = 'pedidos';
        $this->view = 'relatorios.ordemproducao';
        $this->data = $data;
    }

}