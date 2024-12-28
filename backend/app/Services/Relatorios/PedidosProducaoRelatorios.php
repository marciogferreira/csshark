<?php
namespace App\Services\Relatorios;

use App\Models\Cor;
use App\Models\EstoqueProducao;
use App\Models\OrdemProducao;
use App\Models\Pedido;
use App\Models\Produto as Model;
use App\Models\Produto;
use App\Models\ProdutoMontagem;
use App\Models\ProdutoProducao;
use App\Models\StatusProducao;
use App\Services\NegociosServices;
use App\Services\PedidosServices;
use App\Services\OrdemProducaoNegocios;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use stdClass;

class PedidosProducaoRelatorios extends RelatoriosServices {
    
    protected $services;

    public function config($params) {
        

        // INICIO DE ADAPTACAO

        // $data = OrdemProducao::find($params['ordem_producao_id']);
        
        // $produtos_agrupados = [];
        // $estoques = [];
        // $produtos_com_estoque = [];

        // if($data) {

        //     $pedidos = Pedido::where('ordem_producao_correto_id', $data->id)->get();
    
        //     foreach($pedidos as $pedido) {

        //         $pedido->cliente;
        //         $pedido->vendedor;
        //         $pedido->itens;
        //         $pedido->data = Carbon::parse($pedido->created_at)->format('d/m/Y H:i:s');
    
        //         $total = 0;
        //         foreach($pedido->itens as $produto) {
        //             $produto->produto;
        //             $total = $total + NegociosServices::getCalcularDescontoPorPedidoId($pedido->id, $produto->id);
        //             // $total = $total + ($produto->quantidade * $produto->preco);
        //         }
    
        //         // $item->total = $total - ($total * $item->desconto / 100);
        //         $pedido->total = $total;
    
        //         foreach($pedido->itens as $item) {
    
        //             $produto = Produto::find($item->produto_id);
        //             if($produto->quantidade > 0) {
        //                 $produtos_com_estoque[] = [
        //                     'codigo' => $produto->codigo,
        //                     'titulo' => $produto->titulo,
        //                     'quantidade_estoque' => $produto->quantidade,
        //                     'quantidade_pedido' => $item->quantidade,
        //                     'falta' => $item->quantidade - $produto->quantidade
        //                 ];
        //                 $item->quantidade -= $produto->quantidade;
        //             }

        //             $produtoProducao = ProdutoProducao::where('produto_id', $produto->id)->get();
        //             $producao = [];
        //             foreach($produtoProducao as $produtoStatus) {
        //                 $producao[] = $produtoStatus->statusProducao->nome;
        //             }


        //             foreach($produtoProducao as $produtoStatus) {
        //                 if(!isset($produtos_agrupados[$produtoStatus->statusProducao->nome])) {
        //                     $produtos_agrupados[$produtoStatus->statusProducao->nome] = array();
        //                 }

        //                 foreach($produto->itensMontagem as $itemMontagem) {
                            
        //                     if(isset($produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id])) {
        //                         $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->quantidade += ($item->quantidade * $itemMontagem->quantidade);
        //                         $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->quantidade_estoque = 0;
        //                         $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->status_id = $produtoStatus->statusProducao->id;

        //                         $estoqueProducao = EstoqueProducao::where('status_producao_id', $produtoStatus->statusProducao->id)
        //                         ->where('produto_id', $itemMontagem->produto_montagem_id)
        //                         ->get()->first();

        //                         if($estoqueProducao) {
        //                             $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->quantidade_estoque = $estoqueProducao->quantidade;
        //                         }
                                
        //                     } else {

        //                         // $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id] = $itemMontagem->produto;
        //                         // $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->quantidade = ($item->quantidade * $itemMontagem->quantidade);
        //                         // $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]['produto'] = $itemMontagem->produtoMontagem;
        //                         // $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->status_id = $produtoStatus->statusProducao->id;

        //                         $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id] = new stdClass();
        //                         $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->id = $itemMontagem->produto->id;
        //                         $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->produto_montagem_id = $itemMontagem->produtoMontagem->id;
        //                         $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->quantidade = ($item->quantidade * $itemMontagem->quantidade);
        //                         $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->titulo = $itemMontagem->produtoMontagem->titulo;
        //                         $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->produto_venda = $itemMontagem->produto->titulo;
        //                         $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->status_id = $produtoStatus->statusProducao->id;
        //                         $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->producao = $producao;
        //                         $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->cor = $produto->cores ? $produto->cores->name : '';

        //                         $estoqueProducao = EstoqueProducao::where('status_producao_id', $produtoStatus->statusProducao->id)
        //                         ->where('produto_id', $itemMontagem->produto_montagem_id)
        //                         ->get()->first();
                                
        //                         if($estoqueProducao) {
        //                             $produtos_agrupados[$produtoStatus->statusProducao->nome][$itemMontagem->produto_montagem_id]->quantidade_estoque = $estoqueProducao->quantidade;
        //                         }
        //                     }
        //                 }
        
        //                 if(count($produto->itensMontagem) == 0) {
        //                     if(isset($produtos_agrupados[$item->produto_id])) {
        //                         $produtos_agrupados[$produtoStatus->statusProducao->nome][$item->produto_id]->quantidade += $item->quantidade;
        //                     } else {
        //                         $produtos_agrupados[$produtoStatus->statusProducao->nome][$item->produto_id] = $item;
        //                         $produtos_agrupados[$produtoStatus->statusProducao->nome][$item->produto_id]['produto'] = $item->produto;
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }
       
        // $result_produtos_agrupados = [];
        // foreach($produtos_agrupados as $key => $item) {
        //     foreach($item as $produto) {
        //         $result_produtos_agrupados[$key][] = $produto;
        //     }
        // }

        // if($data) {
        //     $data->produtos_agrupados = $result_produtos_agrupados;
        //     // $data->produtos_agrupados = $result_produtos_agrupados;
        //     $data->pedidos = $pedidos;
        // }
        
        // // echo '<pre>'; print_r($data); die;
        // foreach($data->produtos_agrupados as $key => $item) {   
        //     foreach($data->produtos_agrupados[$key] as $produto) {
        //         $produto->quantidade_estoque = 0;
        //     }
        // }
        // $result = [];
        // foreach($data->produtos_agrupados as $key => $item) {
            
        //     $result[$key] = [];
        //     foreach($data->produtos_agrupados[$key] as $produto) {
                
        //         $resultLancarProduto = DB::select("
        //             SELECT sum(quantidade) as quantidade 
        //             FROM estoque_producaos
        //             WHERE status_producao_id = (SELECT id FROM status_producaos WHERE nome = '{$key}')
        //             AND produto_id = {$produto->produto_montagem_id}
        //             GROUP BY produto_id, status_producao_id
        //         ");
                
        //         if(count($resultLancarProduto)) {
        //             $produto->quantidade_estoque = $resultLancarProduto[0]->quantidade;
        //         }
                
        //         $dadosProdutos = array();
        //         $dadosProdutos['id'] = $produto->id;
        //         $dadosProdutos['produto_montagem_id'] = $produto->produto_montagem_id;
        //         $dadosProdutos['status_id'] = $produto->status_id;
        //         $dadosProdutos['titulo'] = $produto->titulo;

        //         $dadosProdutos['cor'] = '';
        //         $statusProducao = StatusProducao::find($produto->status_id);
        //         if($statusProducao->agrupar_por == 'cor') {
        //             $dadosProdutos['cor'] = $produto->cor;
        //             $dadosProdutos['titulo'] .= ' '.$produto->cor;
        //         }

        //         $dadosProdutos['quantidade_estoque'] = $produto->quantidade_estoque;
        //         $dadosProdutos['quantidade'] = $produto->quantidade;
        //         $dadosProdutos['possui'] = $produto->quantidade_estoque - $produto->quantidade;


        //         $dadosProdutos['pedido'] = $produto->quantidade;
        //         $dadosProdutos['pedido_original'] = (int) $produto->quantidade;
        //         $dadosProdutos['estoque'] = 0;
        //         $dadosProdutos['estoque'] = $produto->quantidade_estoque;
        //         $dadosProdutos['falta'] = 0;
        //         $dadosProdutos['producao'] = $produto->producao;
                
        //         $result[$key]['produtos'][] = $dadosProdutos;
        //     }
        // }
        
        // $list = [];
        // foreach($result as $key => $item) {
        //     $item['status'] = $key;
        //     $item['status_producao'] = $key;
        //     $list[] = $item;
        // }

        // $lista = [];
        // foreach($list as $item) {
        //     $produtos = [];
        //     foreach($item['produtos'] as $keyProd => $produto) {
        //         $produtos[$produto['produto_montagem_id']] = $produto;
        //     }
        //     $lista[] = [
        //         'produtos' => $produtos,
        //         'status' => $item['status']
        //     ];
        // }

        // $lista = OrdemProducaoNegocios::filtroProdutosQuantidadePedidoStatus($lista);

        // $statusProducao = StatusProducao::find($params['status_producao_id']);
        // foreach($lista as $key => $item) {
        //     if($item['status'] != $statusProducao->nome) {
        //         unset($lista[$key]);
        //     }
        // }

        // ----------------------------------------------------------------
        $listaProdutos = [];
        $listaSemiProdutos = [];
        $listaEstoque = [];
        $listaStatus = [];
    
        $this->model = new Model();
        $data = $this->model->find($params['ordem_producao_id']);
        $pedidos = Pedido::where('ordem_producao_correto_id', $data->id)->get();
    
        // ALIMENTA PRODUTOS
        foreach($pedidos as $pedido) {
            $itens = $pedido->itens;
            foreach($itens as $item) {
            
                // ARMAZENAR PRODUTOS
                if(isset($listaProdutos[$item->produto->id])) {
                    $listaProdutos[$item->produto->id]['quantidade'] +=  $item->quantidade;
                    $listaProdutos[$item->produto->id]['quantidade_original'] +=  $item->quantidade;
                } else {
                    $listaProdutos[$item->produto->id] = $item->produto->toArray();
                    $listaProdutos[$item->produto->id]['quantidade_original'] = $item->quantidade;
                    $listaProdutos[$item->produto->id]['quantidade'] = $item->quantidade - $item->produto->quantidade;
                    $listaProdutos[$item->produto->id]['estoque'] = $item->produto->quantidade;
                }
            }
        }
    
        // ALIMENTA ESTOQUE
        foreach($listaProdutos as $produto) {
            $itensMontagem = ProdutoMontagem::where('produto_id', $produto['id'])->get();
            foreach($itensMontagem as $itemMontagem) {
                $estoques = EstoqueProducao::where('produto_id', $itemMontagem->produto_montagem_id)
                    ->get();
                foreach($estoques as $estoque) { 
                    if(!isset($listaEstoque[$estoque->produto_id.$estoque->status_producao_id])) {
                        // $listaEstoque[$estoque->produto_id.$estoque->status_producao_id] = $estoque->toArray();
                        $listaEstoque[$estoque->produto_id.$estoque->status_producao_id.$estoque->cor_id] = $estoque->toArray();
                    } 
                }
                
            }
        }
    
        // ALIMENTA STATUS
        foreach($listaProdutos as $produto) {
            $status = ProdutoProducao::where('produto_id', $produto['id'])->get();
            foreach($status as $item) {
                if(!isset($listaStatus[$item->statusProducao->id])) {
                    $listaStatus[$item->statusProducao->id] = [
                        'id' => $item->statusProducao->id,
                        'nome' => $item->statusProducao->nome,
                        'agrupar_por' => $item->statusProducao->agrupar_por
                    ];
                }
            }
        }
        
        // APLICA PRODUTO A STATUS PERTENCENTE
        foreach($listaStatus as $status) {
            foreach($listaProdutos as $produto) { 
                $statusProducao = ProdutoProducao::where('produto_id', $produto['id'])
                    ->where('status_producao_id', $status['id'])->get()->first();
                if($statusProducao) {
                    // echo '<pre>'; print_r($produto); die;
                    $listaStatus[$status['id']]['produtos'][] = [
                        'id' => $produto['id'],
                        'nome' => $produto['titulo'],
                        'quantidade' => $produto['quantidade'],
                        'cor_id' => $produto['cor_id']
                        // 'cor' => $produto->cor->nome,
                    ];
                }
            }
        }
    
        // CONSULTANDO ITENS DE PRODUTOS
        foreach($listaStatus as $status) {
            $listaStatus[$status['id']]['itens'] = array();
            foreach($status['produtos'] as $produto) { 
    
                $produtoRes = Produto::find($produto['id']);
                $itens = $produtoRes->itensMontagem;
                $cor_id = $produtoRes->cor_id ?? 0;
                $cor_id_ = '_'. $cor_id;
    
                foreach($itens as $item) {
                    $produtoArray = $item->produtoMontagem->toArray();
                    if(isset($listaStatus[$status['id']]['itens'][$produtoArray['id'].$cor_id_])) {
                        $listaStatus[$status['id']]['itens'][$produtoArray['id'].$cor_id_]['quantidade'] += $produto['quantidade'] * $item->quantidade;
                        $listaStatus[$status['id']]['itens'][$produtoArray['id'].$cor_id_]['_quantidade'] += $produto['quantidade'] * $item->quantidade;
                        $listaStatus[$status['id']]['itens'][$produtoArray['id'].$cor_id_]['_falta'] += $produto['quantidade'] * $item->quantidade;
                    } else {
                        $listaStatus[$status['id']]['itens'][$produtoArray['id'].$cor_id_] = $produtoArray;
                        $listaStatus[$status['id']]['itens'][$produtoArray['id'].$cor_id_]['quantidade'] = $produto['quantidade'] * $item->quantidade;
                        $listaStatus[$status['id']]['itens'][$produtoArray['id'].$cor_id_]['_quantidade'] = $produto['quantidade'] * $item->quantidade;
                        $listaStatus[$status['id']]['itens'][$produtoArray['id'].$cor_id_]['_estoque'] = 0;
                        $listaStatus[$status['id']]['itens'][$produtoArray['id'].$cor_id_]['_falta'] = $produto['quantidade'] * $item->quantidade;
                        $listaStatus[$status['id']]['itens'][$produtoArray['id'].$cor_id_]['cor_id'] = $cor_id;
                        
                    }
                }
            }
        }
          
        $listEstoqueFiltrada = [];
        foreach ($listaEstoque as $estoque) {
            if($estoque['quantidade'] > 0) {
                $listEstoqueFiltrada[] = $estoque;
            }
        }
      
        foreach ($listaStatus as $key => $item) {
            foreach ($item['itens'] as $keySemi => $semiProduto) {
                foreach ($listEstoqueFiltrada as $keyEstoque => $estoque) {
                    if($listEstoqueFiltrada[$keyEstoque]['quantidade'] > 0) {
                        if($estoque['cor_id'] == $semiProduto['cor_id'] 
                            && $estoque['produto_id'] == $semiProduto['id']
                            && $estoque['status_producao_id'] == $item['id']) {
                            // $listaStatus[$key]['itens'][$keySemi]['quantidade'] -= $estoque['quantidade'];
                            $listaStatus[$key]['itens'][$keySemi]['_quantidade'] = $listaStatus[$key]['itens'][$keySemi]['quantidade'];
                            $listaStatus[$key]['itens'][$keySemi]['_estoque'] = $estoque['quantidade'];
                            $listaStatus[$key]['itens'][$keySemi]['_falta'] = $listaStatus[$key]['itens'][$keySemi]['quantidade'] - $estoque['quantidade'];
                            $listEstoqueFiltrada[$keyEstoque]['quantidade'] = 0;
                        } else {
                            if($listEstoqueFiltrada[$keyEstoque]['quantidade'] > 0) {
                                if($item['agrupar_por'] == 'semi_produto'
                                    && $estoque['produto_id'] == $semiProduto['id']
                                    && $estoque['status_producao_id'] == $item['id']) {
                                    // $listaStatus[$key]['itens'][$keySemi]['quantidade'] -= $estoque['quantidade'];
                                    $listaStatus[$key]['itens'][$keySemi]['_quantidade'] = $listaStatus[$key]['itens'][$keySemi]['quantidade'];
                                    $listaStatus[$key]['itens'][$keySemi]['_estoque'] = $estoque['quantidade'];
                                    $listaStatus[$key]['itens'][$keySemi]['_falta'] = $listaStatus[$key]['itens'][$keySemi]['quantidade'] - $estoque['quantidade'];
                                    $listEstoqueFiltrada[$keyEstoque]['quantidade'] = 0;
                                }
                            }
                        }
                    }
                }
            }
        }
    
        $listaStatus = array_reverse($listaStatus);
    
        // SUBTRAINDO PEDIDOS COM ESTOQUE DE CADEIA DE STATUS
        foreach ($listaStatus as $key => $item) {
            foreach ($item['itens'] as $keySemi => $semiProduto) {
                
                if($semiProduto['_falta'] > 0) {
    
                    $indice = 1;
                    $achou = false;
    
                    while(!$achou){
                        if(isset($listaStatus[$key + $indice])) {
    
                            if(($key + $indice) == 5 && $keySemi == '190_1') {
                                // echo '<pre>'; print_r($item); die;
                                // echo '<pre>'; print_r($listaStatus[$key + $indice]['itens']); die;
                            }
                            foreach ($listaStatus[$key + $indice]['itens'] as $keyProximo => $itemProximo) {
                                if($keySemi == $keyProximo) {
                                    $quantidade =  $listaStatus[$key]['itens'][$keyProximo]['_falta'];
                                    $listaStatus[$key + $indice]['itens'][$keyProximo]['_quantidade'] = $quantidade;
                                    $listaStatus[$key + $indice]['itens'][$keyProximo]['_falta'] = $quantidade - $listaStatus[$key + $indice]['itens'][$keyProximo]['_estoque'];
                                    $achou = true;
                                }
                            }
    
                            if($achou == false) {
                                $indice++;
                            }
    
                        } else {
                            $achou = true;
                        }
                    }                          
                }
    
            }
        }
        // $listaStatus = array_reverse($listaStatus);
        $cores = Cor::all()->toArray();
        
        // AGRUPAR POR COR E SEMIPRODUTO
        foreach ($listaStatus as $key => $item) {
            $itens = [];
            if($item['agrupar_por'] == 'semi_produto') {
                foreach ($item['itens'] as $keySemi => $semiProduto) {
                    if(isset($itens[$semiProduto['id']])) {
                        $itens[$semiProduto['id']]['quantidade'] += $semiProduto['quantidade'];
                        $itens[$semiProduto['id']]['_quantidade'] += $semiProduto['_quantidade'];
                        $itens[$semiProduto['id']]['_estoque'] += $semiProduto['_estoque'];
                        $itens[$semiProduto['id']]['_falta'] += $semiProduto['_falta'];
                    } else {
                        $itens[$semiProduto['id']] = $semiProduto;
                    }
                }
                $listaStatus[$key]['itens'] = $itens;
            }
    
            if($item['agrupar_por'] == 'cor') {
                foreach ($item['itens'] as $keySemi => $semiProduto) {
                    foreach($cores as $cor) {
                        if($cor['id'] == $semiProduto['cor_id']) {
                            $listaStatus[$key]['itens'][$keySemi]['cor'] = $cor['name'];
                        }
                    }
                }
            }
           
        }

        $listaItens = array();
        $statusProducao = StatusProducao::find($params['status_producao_id']);
        foreach($listaStatus as $key => $item) {
            if($item['nome'] != $statusProducao->nome) {
                unset($listaStatus[$key]);
            } else {
                $listaItens[] = [
                    'produtos' => $item['itens'],
                    'status' => $item['nome']
                ];
            }
        }

        // echo '<pre>'; print_r($listaItens); die;

        // FIM DE ADAPTACAO

        $this->name = 'pedidos-producao';
        $this->view = 'relatorios.teste1';
        
        $this->data = [
            'produtos' => [],
            'lista' => $listaItens
        ];
    }

}