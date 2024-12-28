<?php
namespace App\Services\Relatorios;

use App\Models\EstoqueProducao;
use App\Models\LancarProduzido;
use App\Models\OrdemProducao as Model;
use App\Models\Pedido;
use App\Models\Produto;
use App\Models\StatusProducao;
use App\Models\ProdutoProducao;
use App\Services\NegociosServices;
use App\Services\OrdemProducaoNegocios;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use stdClass;

class OrdemProducaoStatusRelatorios extends RelatoriosServices {
    
    public function config($params) {
        $this->model = new Model();

        $data = $this->model->find($params['id']);
        $produtos_agrupados = [];
        $estoques = [];
        $produtos_com_estoque = [];

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
                }
    
                $pedido->total = $total;
    
                foreach($pedido->itens as $item) {
    
                    $produto = Produto::find($item->produto_id);
                    if($produto->quantidade > 0) {
                        $produtos_com_estoque[] = [
                            'codigo' => $produto->codigo,
                            'titulo' => $produto->titulo,
                            'quantidade_estoque' => $produto->quantidade,
                            'quantidade_pedido' => $item->quantidade,
                            'falta' => $item->quantidade - $produto->quantidade
                        ];
                        $item->quantidade -= $produto->quantidade;
                    }

                    $produtoProducao = ProdutoProducao::where('produto_id', $produto->id)->get();
                    $producao = [];
                    foreach($produtoProducao as $produtoStatus) {
                        $producao[] = $produtoStatus->statusProducao->nome;
                    }

                    foreach($produtoProducao as $produtoStatus) {

                        if(!isset($produtos_agrupados[$produtoStatus->statusProducao->nome])) {
                            $produtos_agrupados[$produtoStatus->statusProducao->nome] = array();
                        }

                        foreach($produto->itensMontagem as $itemMontagem) {
                            
                            if(isset($produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id])) {

                                // $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->quantidade += ($item->quantidade * $itemMontagem->quantidade);
                                // $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->quantidade_estoque = 0;
                                // $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->status_id = $produtoStatus->statusProducao->id;
                                $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->quantidade += ($item->quantidade * $itemMontagem->quantidade);
                                $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->quantidade_estoque = 0;
                                $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->status_id = $produtoStatus->statusProducao->id;

                                $estoqueProducao = EstoqueProducao::where('status_producao_id', $produtoStatus->statusProducao->id)
                                ->where('produto_id', $itemMontagem->produto_montagem_id)
                                ->get()->first();

                                if($estoqueProducao) {
                                    $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->quantidade_estoque = $estoqueProducao->quantidade;
                                }
                                
                            } else {

                                // $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id] = $itemMontagem->produto;
                                // $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->quantidade = ($item->quantidade * $itemMontagem->quantidade);
                                // $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]['produto'] = $itemMontagem->produtoMontagem;
                                // $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->status_id = $produtoStatus->statusProducao->id;

                                $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id] = new stdClass();
                                $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->id = $itemMontagem->produto->id;
                                $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->produto_montagem_id = $itemMontagem->produtoMontagem->id;
                                $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->quantidade = ($item->quantidade * $itemMontagem->quantidade);
                                $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->titulo = $itemMontagem->produtoMontagem->titulo;
                                $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->produto_venda = $itemMontagem->produto->titulo;
                                $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->status_id = $produtoStatus->statusProducao->id;
                                $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->producao = $producao;
                                $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->cor = $produto->cores ? $produto->cores->name : '';

                                $estoqueProducao = EstoqueProducao::where('status_producao_id', $produtoStatus->statusProducao->id)
                                ->where('produto_id', $itemMontagem->produto_montagem_id)
                                ->get()->first();
                                
                                if($estoqueProducao) {
                                    $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->quantidade_estoque = $estoqueProducao->quantidade;
                                }
                            }
                        }
        
                        if(count($produto->itensMontagem) == 0) {
                            if(isset($produtos_agrupados[$item->produto_id])) {
                                $produtos_agrupados[$produtoStatus->statusProducao->nome][$item->produto_id]->quantidade += $item->quantidade;
                            } else {
                                $produtos_agrupados[$produtoStatus->statusProducao->nome][$item->produto_id] = $item;
                                $produtos_agrupados[$produtoStatus->statusProducao->nome][$item->produto_id]['produto'] = $item->produto;
                            }
                        }
                    }
                }
            }
        }
       
        $result_produtos_agrupados = [];
        foreach($produtos_agrupados as $key => $item) {
            foreach($item as $produto) {
                $result_produtos_agrupados[$key][] = $produto;
            }
        }

        if($data) {
            $data->produtos_agrupados = $result_produtos_agrupados;
            $data->pedidos = $pedidos;
        }
        
        // echo '<pre>'; print_r($data); die;
        foreach($data->produtos_agrupados as $key => $item) {   
            foreach($data->produtos_agrupados[$key] as $produto) {
                $produto->quantidade_estoque = 0;
            }
        }
        $result = [];
        foreach($data->produtos_agrupados as $key => $item) {
            
            $result[$key] = [];
            foreach($data->produtos_agrupados[$key] as $produto) {
                
                $resultLancarProduto = DB::select("
                    SELECT sum(quantidade) as quantidade 
                    FROM estoque_producaos
                    WHERE status_producao_id = (SELECT id FROM status_producaos WHERE nome = '{$key}')
                    AND produto_id = {$produto->produto_montagem_id}
                    GROUP BY produto_id, status_producao_id
                ");
                
                if(count($resultLancarProduto)) {
                    $produto->quantidade_estoque = $resultLancarProduto[0]->quantidade;
                }
                $dadosProdutos = array();
                $dadosProdutos['id'] = $produto->id;
                $dadosProdutos['produto_montagem_id'] = $produto->produto_montagem_id;
                $dadosProdutos['status_id'] = $produto->status_id;
                
                $dadosProdutos['titulo'] = $produto->titulo;

                $dadosProdutos['cor'] = '';
                $statusProducao = StatusProducao::find($produto->status_id);
                if($statusProducao->agrupar_por == 'cor') {
                    $dadosProdutos['cor'] = $produto->cor;
                }
                
                
                $dadosProdutos['quantidade_estoque'] = $produto->quantidade_estoque;
                $dadosProdutos['quantidade'] = $produto->quantidade;
                $dadosProdutos['possui'] = $produto->quantidade_estoque - $produto->quantidade;


                $dadosProdutos['pedido'] = $produto->quantidade;
                $dadosProdutos['pedido_original'] = (int) $produto->quantidade;
                $dadosProdutos['estoque'] = 0;
                $dadosProdutos['estoque'] = $produto->quantidade_estoque;
                $dadosProdutos['falta'] = 0;
                $dadosProdutos['producao'] = $produto->producao;
                


                if(empty($dadosProdutos['titulo'])) {
                    continue;
                }
                
                $result[$key]['produtos'][] = $dadosProdutos;
            }
        }

        // ESTOQUES - REMOVI 19/09/23
        // foreach($result as $key => $item) {
        //     foreach($item['produtos'] as $produto) {
                
        //         if(!isset($estoques[$key])) {
        //             $estoques[$key] = [];
        //         }

        //         if(!isset($estoques[$key][$produto['produto_montagem_id']])) {
        //             // $estoques[$key][$produto['produto_montagem_id']] = $produto['estoque'];
        //         } else {
        //             // $estoques[$key][$produto['produto_montagem_id']] += $produto['estoque'];
        //         }
        
                
        //         foreach($produto['producao'] as $keyStatus => $status) {
        //             if($key == $status) {
        //                 $contador = $keyStatus;
        //             }
        //         }
                
        //         foreach($produto['producao'] as $keyStatus => $status) {
        //             if($contador > $keyStatus) {
        //                 if(!isset($estoques[$status])) {
        //                     $estoques[$status] = [];
        //                 }
        //                 if($key != $status) {
        //                     if(!isset($estoques[$status][$produto['id']])) {
        //                         $estoques[$status][$produto['produto_montagem_id']] = $produto['estoque'];
        //                     } else {
        //                         $estoques[$status][$produto['produto_montagem_id']] = $estoques[$status][$produto['produto_montagem_id']] + intval($produto['estoque']);
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }
        
        $list = [];
        foreach($result as $key => $item) {
            $item['status'] = $key;
            $item['status_producao'] = $key;
            $list[] = $item;
        }


        // $list = array_reverse($list);
        $result = $list;
        $novaLista = [];

        

        // foreach($list as $key => $item) {
        //     foreach($item['produtos'] as $keyProduto => $produto) {

        //         if(isset($estoques[$item['status']][$produto['produto_montagem_id']])) {
        //             if($list[$key]['produtos'][$keyProduto]['estoque'] <= 0) {
        //                 $list[$key]['produtos'][$keyProduto]['estoque'] = $estoques[$item['status']][$produto['produto_montagem_id']];
        //             } else {
        //                 $list[$key]['produtos'][$keyProduto]['estoque'] += $estoques[$item['status']][$produto['produto_montagem_id']];
        //             }
        //         }    

        //         // $result[$key]['produtos'][$keyProduto]['pedido'] = $result[$key]['produtos'][$keyProduto]['pedido_original'] - $result[$key]['produtos'][$keyProduto]['estoque'];
        //         $list[$key]['produtos'][$keyProduto]['falta'] = $list[$key]['produtos'][$keyProduto]['pedido'] - $list[$key]['produtos'][$keyProduto]['estoque'];
                
        //         if(isset($list[$key + 1])) {
                                      
        //             if(($list[$key]['produtos'][$keyProduto]['pedido'] - $list[$key]['produtos'][$keyProduto]['estoque']) > 0) {

        //                 if(isset($list[$key + 1]['produtos'][$keyProduto])) {
        //                     if($list[$key + 1]['produtos'][$keyProduto]['id'] ==  $list[$key]['produtos'][$keyProduto]['id']) {
        //                         if($list[$key]['produtos'][$keyProduto]['estoque'] > 0) {
        //                             $list[$key + 1]['produtos'][$keyProduto]['pedido'] = $list[$key]['produtos'][$keyProduto]['pedido'] - $list[$key]['produtos'][$keyProduto]['estoque'];
        //                         } else {
        //                             if($list[$key + 1]['produtos'][$keyProduto]['pedido'] == $list[$key]['produtos'][$keyProduto]['pedido']) {
        //                                 $list[$key + 1]['produtos'][$keyProduto]['pedido'] = $list[$key]['produtos'][$keyProduto]['pedido'] - $list[$key]['produtos'][$keyProduto]['estoque'];
        //                             }
        //                         }
        //                     }
        //                 }

        //             } else {
        //                 $list[$key + 1]['produtos'][$keyProduto]['pedido'] = 0;
        //             }
        //         }
        //     }
           
        // }

        // ADD 19/09/23
        $lista = [];
        foreach($list as $item) {
            $produtos = [];
            foreach($item['produtos'] as $keyProd => $produto) {
                $produtos[$produto['produto_montagem_id']] = $produto;
            }
            $lista[] = [
                'produtos' => $produtos,
                'status' => $item['status']
            ];
        }

        // foreach($lista as $key => $itemStatus) {
        //     foreach($itemStatus['produtos'] as $item) {
        //         $key = (int) $key;

        //         if(isset($lista[$key + 1])) {
        //             if(isset($item['produto_montagem_id'])) {
        //                 if(isset($lista[$key + 1]['produtos'][$item['produto_montagem_id']])) {
                            
        //                     $pedido = $lista[$key]['produtos'][$item['produto_montagem_id']]['pedido'] - ($item['estoque'] > 0 ? $item['estoque'] : 0);
        //                     $lista[$key]['produtos'][$item['produto_montagem_id']]['falta'] = $lista[$key]['produtos'][$item['produto_montagem_id']]['pedido'] - $item['estoque'];

        //                     $listaStatusProducaoRecente = $lista[$key]['produtos'][$item['produto_montagem_id']]['producao'];
        //                     $pedidoFuturo = $lista[$key + 1]['produtos'][$item['produto_montagem_id']]['pedido'];
        //                     $statusFuturo = $lista[$key + 1]['status'];
        //                     // echo '<pre>'; print_r(in_array($statusFuturo, $listaStatusProducaoRecente)); die;
        //                     if($pedidoFuturo > $pedido && !in_array($statusFuturo, $listaStatusProducaoRecente)) {
        //                         $pedido = $pedidoFuturo;
        //                     }
        //                     $lista[$key + 1]['produtos'][$item['produto_montagem_id']]['pedido'] = $pedido > 0 ? $pedido : 0;
        //                     $lista[$key + 1]['produtos'][$item['produto_montagem_id']]['falta'] = ($pedido > 0 ? $pedido : 0) - $lista[$key + 1]['produtos'][$item['produto_montagem_id']]['estoque'];

        //                 } else {

        //                     $indice = 2;
        //                     $checkExists = true;
                            
        //                     while(isset($lista[$key + $indice]) && $checkExists) {
        //                         if(isset($lista[$key + $indice])) {
        //                             if(isset($lista[$key + $indice]['produtos'][$item['produto_montagem_id']])) {
        //                                 $pedido = $lista[$key]['produtos'][$item['produto_montagem_id']]['pedido'] - ($lista[$key]['produtos'][$item['produto_montagem_id']]['estoque'] > 0 ? $lista[$key]['produtos'][$item['produto_montagem_id']]['estoque'] : 0);
        //                                 $lista[$key]['produtos'][$item['produto_montagem_id']]['falta'] = $pedido;
        //                                 $listaStatusProducaoRecente = $lista[$key]['produtos'][$item['produto_montagem_id']]['producao'];
                                        
        //                                 $pedidoFuturo = $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['pedido'];
        //                                 $statusFuturo = $lista[$key + $indice]['status'];
                                        
                                        
        //                                 if($pedidoFuturo > $pedido && !in_array($statusFuturo, $listaStatusProducaoRecente)) {
        //                                     $pedido = $pedidoFuturo;
        //                                 }

        //                                 $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['pedido'] = $pedido > 0 ? $pedido : 0;
        //                                 $falta = $pedido - $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['estoque'];
        //                                 $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['falta'] = $falta < 0 ? 0 : $falta;
        //                                 $checkExists = false;
        //                             }
        //                         }
        //                         if($indice > 15) {
        //                             $checkExists = false;
        //                         }
        //                         $indice++;
        //                     }                       
        //                 }
        //             }
        //         }
        //     }
        // }
        $lista = OrdemProducaoNegocios::filtroProdutosQuantidadePedidoStatus($lista);
        // ATE AQUI 19/09/23
        
        // echo '<pre>'; print_r($result); die;
        $this->name = 'pedidos';
        // $this->view = 'relatorios.ordemproducaostatus';
        $this->view = 'relatorios.teste1';
        $this->data = [
            'produtos' => $produtos_com_estoque,
            'lista' => $lista
        ];
    }

}