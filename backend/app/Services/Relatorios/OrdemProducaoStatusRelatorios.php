<?php
namespace App\Services\Relatorios;

use App\Models\EstoqueProducao;
use App\Models\LancarProduzido;
use App\Models\OrdemProducao as Model;
use App\Models\Pedido;
use App\Models\Produto;
use App\Models\StatusProducao;
use App\Models\ProdutoMontagem;
use App\Models\Cor;
use App\Models\ProdutoProducao;
use App\Services\NegociosServices;
use App\Services\OrdemProducaoNegocios;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use stdClass;

class OrdemProducaoStatusRelatorios extends RelatoriosServices {
    
    public function config($params) {

        $listaProdutos = [];
        $listaSemiProdutos = [];
        $listaEstoque = [];
        $listaStatus = [];
    
        $this->model = new Model();
        $data = $this->model->find($params['id']);
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
        // echo '<pre>'; print_r($listaStatus); die;     
        // echo '<pre>'; print_r($listaProdutos); die;
    
        $this->name = 'pedidos';
        $this->view = 'relatorios.teste4';
        // $this->orientation = 'landscape';
        $this->data = [
            'produtos' => $listaProdutos,
            'lista' => $listaStatus
        ];
    }

    // public function config($params) {
    //     $this->model = new Model();

    //     $data = $this->model->find($params['id']);
    //     $produtos_agrupados = [];
    //     $teste = [];
    //     $estoques = [];
    //     $produtos_com_estoque = [];
    //     $produtosCores = [];
        
    //     if($data) {

    //         $pedidos = Pedido::where('ordem_producao_correto_id', $data->id)->get();

    //         foreach($pedidos as $pedido) {

    //             $pedido->cliente;
    //             $pedido->vendedor;
    //             $pedido->itens;
    //             $pedido->data = Carbon::parse($pedido->created_at)->format('d/m/Y H:i:s');

    //             $total = 0;
    //             foreach($pedido->itens as $produto) {
    //                 $produto->produto;
    //                 $total = $total + NegociosServices::getCalcularDescontoPorPedidoId($pedido->id, $produto->id);
    //             }
                
    //             $pedido->total = $total;
                
    //             foreach($pedido->itens as $item) {

    //                 $produto = Produto::find($item->produto_id);
    //                 $nameCor = isset($produto->cores->name) ? $produto->cores->name : 'default';
    //                 if($produto->quantidade > 0) {
    //                     $produtos_com_estoque[] = [
    //                         'codigo' => $produto->codigo,
    //                         'titulo' => $produto->titulo,
    //                         'quantidade_estoque' => $produto->quantidade,
    //                         'quantidade_pedido' => $item->quantidade,
    //                         'falta' => $item->quantidade - $produto->quantidade,
    //                         'cor' => $nameCor,
    //                     ];
    //                     // $item->quantidade -= $produto->quantidade;
    //                 }
                    
    //                 $produtoProducao = ProdutoProducao::where('produto_id', $produto->id)->get();
    //                 $producao = [];
    //                 foreach($produtoProducao as $produtoStatus) {
    //                     $producao[] = $produtoStatus->statusProducao->nome;
    //                 }
                    
    //                 foreach($produtoProducao as $produtoStatus) {

    //                     if(!isset($produtos_agrupados[$produtoStatus->statusProducao->nome])) {
    //                         $produtos_agrupados[$produtoStatus->statusProducao->nome] = array();
    //                     }                   
                        
    //                     foreach($produto->itensMontagem as $itemMontagem) {

    //                         if(isset($produtos_agrupados[$produtoStatus->statusProducao->nome][$nameCor][$itemMontagem->produto_montagem_id])) {

    //                             $produtos_agrupados[$produtoStatus->statusProducao->nome][$nameCor][$itemMontagem->produto_montagem_id]->quantidade += ($item->quantidade * $itemMontagem->quantidade);
    //                             $produtos_agrupados[$produtoStatus->statusProducao->nome][$nameCor][$itemMontagem->produto_montagem_id]->quantidade_estoque = 0;
    //                             $produtos_agrupados[$produtoStatus->statusProducao->nome][$nameCor][$itemMontagem->produto_montagem_id]->status_id = $produtoStatus->statusProducao->id;

    //                             $estoqueProducao = EstoqueProducao::where('status_producao_id', $produtoStatus->statusProducao->id)
    //                             ->where('produto_id', $itemMontagem->produto_montagem_id)
    //                             ->get()->first();

    //                             if($estoqueProducao) {
    //                                 $produtos_agrupados[$produtoStatus->statusProducao->nome][$nameCor][$itemMontagem->produto_montagem_id]->quantidade_estoque = $estoqueProducao->quantidade;
    //                             }
                                
    //                         } else {

    //                             $produtos_agrupados[$produtoStatus->statusProducao->nome][$nameCor][$itemMontagem->produto_montagem_id] = new stdClass();
    //                             $produtos_agrupados[$produtoStatus->statusProducao->nome][$nameCor][$itemMontagem->produto_montagem_id]->id = $itemMontagem->produto->id;
    //                             $produtos_agrupados[$produtoStatus->statusProducao->nome][$nameCor][$itemMontagem->produto_montagem_id]->produto_montagem_id = $itemMontagem->produtoMontagem->id;
    //                             $produtos_agrupados[$produtoStatus->statusProducao->nome][$nameCor][$itemMontagem->produto_montagem_id]->quantidade = ($item->quantidade * $itemMontagem->quantidade);
    //                             $produtos_agrupados[$produtoStatus->statusProducao->nome][$nameCor][$itemMontagem->produto_montagem_id]->titulo = $itemMontagem->produtoMontagem->titulo;
    //                             $produtos_agrupados[$produtoStatus->statusProducao->nome][$nameCor][$itemMontagem->produto_montagem_id]->produto_venda = $itemMontagem->produto->titulo;
    //                             $produtos_agrupados[$produtoStatus->statusProducao->nome][$nameCor][$itemMontagem->produto_montagem_id]->status_id = $produtoStatus->statusProducao->id;
    //                             $produtos_agrupados[$produtoStatus->statusProducao->nome][$nameCor][$itemMontagem->produto_montagem_id]->producao = $producao;
                                
    //                             if( $itemMontagem->produto->cor_id) {
    //                                 $produtos_agrupados[$produtoStatus->statusProducao->nome][$nameCor][$itemMontagem->produto_montagem_id]->cor = $itemMontagem->produto->cores->name;
    //                             } else {
    //                                 $produtos_agrupados[$produtoStatus->statusProducao->nome][$nameCor][$itemMontagem->produto_montagem_id]->cor = null;
    //                             }                          

    //                             $estoqueProducao = EstoqueProducao::where('status_producao_id', $produtoStatus->statusProducao->id)
    //                             ->where('produto_id', $itemMontagem->produto_montagem_id)
    //                             ->get()->first();
                                
    //                             if($estoqueProducao) {
    //                                 $produtos_agrupados[$produtoStatus->statusProducao->nome][$nameCor][$itemMontagem->produto_montagem_id]->quantidade_estoque = $estoqueProducao->quantidade;
    //                             }
    //                         }
    //                     }
        
    //                     if(count($produto->itensMontagem) == 0) {
    //                         if(isset($produtos_agrupados[$item->produto_id])) {
    //                             $produtos_agrupados[$produtoStatus->statusProducao->nome][$item->produto_id]->quantidade += $item->quantidade;
    //                         } else {
    //                             // $produtos_agrupados[$produtoStatus->statusProducao->nome][$item->produto_id] = $item;
    //                             $produtos_agrupados[$produtoStatus->statusProducao->nome][$item->produto_id] = $item->id;
    //                             $produtos_agrupados[$produtoStatus->statusProducao->nome][$item->produto_id]['produto'] = $item->produto;
    //                         }
    //                     }
    //                 }
                    
    //             }

    //         }
    //     }
        
    //     $result_produtos_agrupados = [];
    //     // echo '<pre>'; print_r($produtos_com_estoque); die;
    //     // foreach($produtos_agrupados as $key => $item) {
    //     //     foreach($item as $produto) {
    //     //         $result_produtos_agrupados[$key][] = $produto;
    //     //     }
    //     // }   
        
    //     $results = array();

    //     // if($data) {
    //     //     $data->produtos_agrupados = $result_produtos_agrupados;
    //     //     $data->pedidos = $pedidos;
    //     // }
        
    //     // echo '<pre>'; print_r($data); die;
    //     // foreach($data->produtos_agrupados as $key => $item) {   
    //     //     foreach($data->produtos_agrupados[$key] as $produto) {
    //     //         $produto->quantidade_estoque = 0;
    //     //     }
    //     // }

    //     $result = [];
    //     // echo '<pre>'; print_r($produtos_agrupados); die;

    //     foreach($produtos_agrupados as $key => $item) {
    //         foreach($item as $cor => $itemCor) {
                
    //             $result[$key][$cor] = [];
    //             foreach($itemCor as $keyPoduto => $produto) {
    //                 // echo '<pre>'; print_r($produtoitem); die;
                    
    //                 $resultLancarProduto = DB::select("
    //                     SELECT sum(quantidade) as quantidade 
    //                     FROM estoque_producaos
    //                     WHERE status_producao_id = (SELECT id FROM status_producaos WHERE nome = '{$key}')
    //                     AND produto_id = {$produto->produto_montagem_id}
    //                     GROUP BY produto_id, status_producao_id
    //                 ");
                    
                    
    //                 if(count($resultLancarProduto)) {
    //                     $produto->quantidade_estoque = $resultLancarProduto[0]->quantidade;
    //                 } else {
    //                     $produto->quantidade_estoque = 0;
    //                 }

    //                 $dadosProdutos = array();
    //                 // echo '<pre>'; print_r($produto); die;
    //                 $dadosProdutos['id'] = $produto->id;
    //                 $dadosProdutos['produto_montagem_id'] = $produto->produto_montagem_id;
                    
    //                 $dadosProdutos['status_id'] = $produto->status_id;
        
    //                 if(StatusProducao::find($produto->status_id)->agrupar_por == 'cor') {
    //                     $dadosProdutos['titulo'] = $produto->titulo.' '.$produto->cor;
    //                 } else {
    //                     $dadosProdutos['titulo'] = $produto->titulo;
    //                 }
                    
    //                 $dadosProdutos['quantidade_estoque'] = $produto->quantidade_estoque;
    //                 $dadosProdutos['quantidade'] = $produto->quantidade;
    //                 $dadosProdutos['possui'] = $produto->quantidade_estoque - $produto->quantidade;
        
    //                 $dadosProdutos['pedido'] = $produto->quantidade;
    //                 $dadosProdutos['pedido_original'] = (int) $produto->quantidade;
    //                 $dadosProdutos['estoque'] = 0;
    //                 $dadosProdutos['estoque'] = $produto->quantidade_estoque;
    //                 $dadosProdutos['falta'] = 0;
    //                 $dadosProdutos['producao'] = $produto->producao;
        
    //                 if(empty($dadosProdutos['titulo'])) {
    //                     continue;
    //                 }
    //                 $result[$key][$cor]['produtos'][] = $dadosProdutos;
    //             }
    //         }
    //     }
    //     // echo '<pre>'; print_r($result); die;
    //     $list = [];
    //     foreach($result as $key => $item) {
    //         $item['status'] = $key;
    //         $item['status_producao'] = $key;
    //         $list[] = $item;
    //     }    


    //     // $list = array_reverse($list);
    //     $result = $list;
    //     $novaLista = [];

    //     $lista = [];
        
    //     foreach($list as $item) {
    //         foreach($item as $cor => $itemCor) {
                
    //             $produtos = [];
    //             if(isset($itemCor['produtos'])) {
    //                 foreach($itemCor['produtos'] as $produto) {
    //                     $produtos[$produto['produto_montagem_id']] = $produto;
    //                 }

    //                 //  $lista[] = [
    //                 //     'produtos' => $produtos,
    //                 //     'status' => $item['status']
    //                 // ];
    //                 $lista[$item['status']]['produtos'][$cor][] = $produtos;
    //             }
    //         }
    //     }

    //     $listaIndexNum = [];
    //     foreach($lista as $key => $item) {
    //         $item['status'] = $key;
    //         $item['status_producao'] = $key;
    //         $listaIndexNum[] = $item;
    //     }   

    //     // echo '<pre>'; print_r($listaIndexNum); die;
    //     $lista = OrdemProducaoNegocios::filtroProdutosQuantidadePedidoStatus2($listaIndexNum);

    //     // echo '<pre>'; print_r($lista); die;
    //     // echo '<pre>'; print_r($produtos_com_estoque); die;
    //     // echo '<pre>'; print_r($produtos_com_estoque); die;
    //     $results = [];
    //     foreach($lista as $item) {
    //         $res = StatusProducao::where('nome', $item['status'])->get()->first();
            
    //         if($res->agrupar_por == 'semi_produto') {     
    //             $produtosCores = [];
    //             foreach($item['produtos'] as $itemCores) {
    //                 foreach($itemCores as $prod) {
    //                     foreach($prod as $itemProduto) {
    //                         if(isset($produtosCores[$itemProduto['produto_montagem_id']])) {
    //                             $produtosCores[$itemProduto['produto_montagem_id']]['quantidade_estoque'] += $itemProduto['quantidade_estoque'];
    //                             $produtosCores[$itemProduto['produto_montagem_id']]['quantidade'] += $itemProduto['quantidade'];
    //                             $produtosCores[$itemProduto['produto_montagem_id']]['pedido'] += $itemProduto['pedido'];
    //                             $produtosCores[$itemProduto['produto_montagem_id']]['pedido_original'] += $itemProduto['pedido_original'];
    //                             // $produtosCores[$itemProduto['produto_montagem_id']]['estoque'] += $itemProduto['estoque'];
    //                             $produtosCores[$itemProduto['produto_montagem_id']]['estoque'] = $itemProduto['estoque'];
    //                             $produtosCores[$itemProduto['produto_montagem_id']]['possui'] = $itemProduto['possui'];
    //                             $produtosCores[$itemProduto['produto_montagem_id']]['falta'] += $itemProduto['falta'];
    //                         } else {
    //                             $produtosCores[$itemProduto['produto_montagem_id']] = $itemProduto;
    //                         }
    //                     }
                        
    //                 }
    //             }

    //             $results[$item['status']] = [
    //                 'produtos' => $produtosCores,
    //                 'status' => $item['status'],
    //                 'agrupado_por' => 'semi_produto'
    //             ];    
    //         } else {
    //             $results[$item['status']] = [
    //                 'produtos' => $item['produtos'],
    //                 'status' => $item['status'],
    //                 'agrupado_por' => 'cor'
    //             ];
    //         }
    //     }
    //     // echo '<pre>'; print_r($results); die;
    //     foreach($lista as $item) {
    //         // echo '<pre>'; print_r($produtosCores); die;
    //     }
        
    //     // echo '<pre>'; print_r($result); die;
    //     $this->name = 'pedidos';
    //     // $this->view = 'relatorios.ordemproducaostatus';
    //     $this->view = 'relatorios.teste3';
    //     // $this->orientation = 'landscape';
    //     $this->data = [
    //         'produtos' => $produtos_com_estoque,
    //         'lista' => $results
    //     ];
    // }

}