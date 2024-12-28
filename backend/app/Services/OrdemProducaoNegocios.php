<?php
namespace App\Services;

class OrdemProducaoNegocios {

    public static function filtroProdutosQuantidadePedidoStatus($lista) {
        $lista = array_reverse($lista);
    
        foreach($lista as $key => $itemStatus) {
            foreach($itemStatus['produtos'] as $item) {
                $key = (int) $key;
                $produtoAtual = $lista[$key]['produtos'][$item['produto_montagem_id']];
                if(isset($lista[$key + 1])) {
                    if(isset($item['id'])) {

                        if(isset($lista[$key + 1]['produtos'][$item['produto_montagem_id']]) && 
                        $lista[$key]['produtos'][$item['produto_montagem_id']]['id'] == $lista[$key + 1]['produtos'][$item['produto_montagem_id']]['id']
                        ) { 

                            
                            
                            $pedido =  $lista[$key]['produtos'][$item['produto_montagem_id']]['pedido'] - ($item['estoque'] > 0 ? $item['estoque'] : 0);
                            $lista[$key]['produtos'][$item['produto_montagem_id']]['falta'] = $lista[$key]['produtos'][$item['produto_montagem_id']]['pedido'] - $item['estoque'];
                            
                            // PROXIMO
                            if(isset($lista[$key + 1]['produtos'][$item['produto_montagem_id']]['producao'])) {
                                
                                if($item['id'] === $lista[$key + 1]['produtos'][$item['produto_montagem_id']]['id'] && $lista[$key]['produtos'][$item['produto_montagem_id']]['id'] == $lista[$key + 1]['produtos'][$item['produto_montagem_id']]['id']) {
                                    if($item['pedido_original'] != $lista[$key + 1]['produtos'][$item['produto_montagem_id']]['pedido_original']) {
                                        
                                        if(isset($lista[$key + 1]['produtos'][$item['produto_montagem_id']]['zerou'])) {
                                            $lista[$key + 1]['produtos'][$item['produto_montagem_id']]['pedido'] += $pedido;
                                        } else {
                                            $lista[$key + 1]['produtos'][$item['produto_montagem_id']]['zerou'] = true;
                                            $lista[$key + 1]['produtos'][$item['produto_montagem_id']]['pedido'] = 0;
                                            $lista[$key + 1]['produtos'][$item['produto_montagem_id']]['pedido'] += $pedido;
                                        }
                                        
                                        $pedido = $lista[$key + 1]['produtos'][$item['produto_montagem_id']]['pedido'];
                                        $lista[$key + 1]['produtos'][$item['produto_montagem_id']]['pedido'] = $pedido > 0 ? $pedido : 0;
                                        $lista[$key + 1]['produtos'][$item['produto_montagem_id']]['falta'] = ($pedido > 0 ? $pedido : 0) - $lista[$key + 1]['produtos'][$item['produto_montagem_id']]['estoque'];

                                    } else {
                                        // echo '<pre>'; print_r($item); die;
                                        $lista[$key + 1]['produtos'][$item['produto_montagem_id']]['pedido'] = $pedido > 0 ? $pedido : 0;
                                        $lista[$key + 1]['produtos'][$item['produto_montagem_id']]['falta'] = ($pedido > 0 ? $pedido : 0) - $lista[$key + 1]['produtos'][$item['produto_montagem_id']]['estoque'];
                                    }   
                                }   
                            }
                        } else {
    
                            $indice = 1;
                            $checkExists = true;

                            // if($lista[$key]['status'] == 'polimento' && $item['id'] == 32) {
                            //     echo '<pre>'; print_r($item); die;
                            // }
                            
                            while(isset($lista[$key + $indice]) && $checkExists) {
                                if(isset($lista[$key + $indice])) {
                                    if(isset($lista[$key + $indice]['produtos'][$item['produto_montagem_id']])) {

                                        // if($lista[$key]['produtos'][$item['produto_montagem_id']]['id'] == $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['id']) {
                                            $pedido = $lista[$key]['produtos'][$item['produto_montagem_id']]['pedido'] - ($lista[$key]['produtos'][$item['produto_montagem_id']]['estoque'] > 0 ? $lista[$key]['produtos'][$item['produto_montagem_id']]['estoque'] : 0);
                                            $lista[$key]['produtos'][$item['produto_montagem_id']]['falta'] = $pedido;
                                            
                                            // $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['pedido'] = $pedido > 0 ? $pedido : 0;
                                            // PROXIMO
                                            if(isset($lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['producao'])) {    

                                                // VERIFICA SE O ID DO PRODUTO É MESMO PARA REALIZARS A MUDANÇA
                                                if($lista[$key]['produtos'][$item['produto_montagem_id']]['id'] == $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['id']) {
                                                    
                                                    if($item['pedido_original'] != $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['pedido_original']) {
                                                        if($lista[$key]['status'] == 'polimento' && $item['id'] == 32) {
                                                            // echo '<pre>111'; print_r($lista[$key + $indice]['produtos'][$item['produto_montagem_id']]); die;
                                                            // echo '<pre>'; print_r($lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['pedido']); die;
                                                        }

                                                        if(isset($lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['zerou'])) {
                                                            $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['pedido'] += $pedido;
                                                        } else {
                                                            $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['zerou'] = true;
                                                            $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['pedido'] = 0;
                                                            $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['pedido'] += $pedido;   
                                                        }

                                                        // $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['pedido'] -= $pedido;
                                                        $pedido = $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['pedido'];
                                                    } else {
                                                        // echo '<pre>'; print_r($item); die;
                                                        $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['pedido'] = $pedido > 0 ? $pedido : 0;
                                                        $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['falta'] = ($pedido > 0 ? $pedido : 0) - $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['estoque'];
                                                    }   
                                                } else {
                                                    // VERIFICAR SE É MESMO STATUS, MESMO PRODUTO_MONTAGEM_ID E SE O PEDIDO É MAIOR
                                                    
                                                    if($lista[$key]['produtos'][$item['produto_montagem_id']]['produto_montagem_id'] == $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['produto_montagem_id']) {
                                                        
                                                        if(in_array($lista[$key + $indice]['status'], $lista[$key]['produtos'][$item['produto_montagem_id']]['producao'])) {
                                                            
                                                            if($lista[$key]['produtos'][$item['produto_montagem_id']]['pedido_original'] != $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['pedido_original']) {
                                                                // echo '<pre>'; print_r($pedido); die;
                                                                if(isset($lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['zerou'])) {
                                                                    $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['pedido'] += $pedido;
                                                                } else {
                                                                    $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['zerou'] = true;
                                                                    $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['pedido'] = 0;
                                                                    $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['pedido'] += $pedido;   
                                                                }
                                                                $pedido = $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['pedido'];
                                                            } else {
                                                                
                                                                // $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['pedido'] = $pedido > 0 ? $pedido : 0;
                                                                if(isset($lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['zerou'])) {
                                                                    $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['pedido'] += $pedido;
                                                                } else {
                                                                    $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['zerou'] = true;
                                                                    $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['pedido'] = 0;
                                                                    $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['pedido'] += $pedido;   
                                                                }
                                                                $pedido = $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['pedido'];
                                                                $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['falta'] = ($pedido > 0 ? $pedido : 0) - $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['estoque'];
                                                            }   
                                                        }
                                                    }
                                                }                                    
                                            }
        
                                            $falta = $pedido - $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['estoque'];
                                            $lista[$key + $indice]['produtos'][$item['produto_montagem_id']]['falta'] = $falta;
                                            // $checkExists = false;
                                        // }
                                        
                                    } 
                                }
                                
                                if($indice > 15) {
                                    $checkExists = false;
                                }
                                $indice++;
                            }                       
                        }
                    }
                }
            }
            // echo '<pre>'; print_r($lista[3]['produtos']['190']);
        }

        return $lista;
    }

    public static function filtroProdutosQuantidadePedidoStatus2($lista) {
        $lista = array_reverse($lista);
    
        foreach($lista as $key => $itemStatus) {
            foreach($itemStatus['produtos'] as $cor => $itemCores) {
                $key = (int) $key;
                foreach($itemCores as $keyProd => $itemLista) {
                    foreach($itemLista as $keyMontagem => $item) {
                        // echo '<pre>'; print_r($lista[$key]['produtos'][$cor][$keyProd][$keyProd][$keyMontagem]); die;
                        
                        $produtoAtual = $lista[$key]['produtos'][$cor][$keyProd][$item['produto_montagem_id']];
                        if(isset($lista[$key + 1])) {
                            if(isset($item['id'])) {

                                if(isset($lista[$key + 1]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]) && 
                                $lista[$key]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['id'] == $lista[$key + 1]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['id']
                                ) { 

                                    
                                    
                                    $pedido =  $lista[$key]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'] - ($item['estoque'] > 0 ? $item['estoque'] : 0);
                                    $lista[$key]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['falta'] = $lista[$key]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'] - $item['estoque'];
                                    
                                    // PROXIMO
                                    if(isset($lista[$key + 1]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['producao'])) {
                                        
                                        if($item['id'] === $lista[$key + 1]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['id'] && $lista[$key]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['id'] == $lista[$key + 1]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['id']) {
                                            if($item['pedido_original'] != $lista[$key + 1]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido_original']) {
                                                
                                                if(isset($lista[$key + 1]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['zerou'])) {
                                                    $lista[$key + 1]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'] += $pedido;
                                                } else {
                                                    $lista[$key + 1]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['zerou'] = true;
                                                    $lista[$key + 1]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'] = 0;
                                                    $lista[$key + 1]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'] += $pedido;
                                                }
                                                
                                                $pedido = $lista[$key + 1]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'];
                                                $lista[$key + 1]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'] = $pedido > 0 ? $pedido : 0;
                                                $lista[$key + 1]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['falta'] = ($pedido > 0 ? $pedido : 0) - $lista[$key + 1]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['estoque'];

                                            } else {
                                                // echo '<pre>'; print_r($item); die;
                                                $lista[$key + 1]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'] = $pedido > 0 ? $pedido : 0;
                                                $lista[$key + 1]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['falta'] = ($pedido > 0 ? $pedido : 0) - $lista[$key + 1]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['estoque'];
                                            }   
                                        }   
                                    }
                                } else {
            
                                    $indice = 1;
                                    $checkExists = true;

                                    // if($lista[$key]['status'] == 'polimento' && $item['id'] == 32) {
                                    //     echo '<pre>'; print_r($item); die;
                                    // }
                                    
                                    while(isset($lista[$key + $indice]) && $checkExists) {
                                        if(isset($lista[$key + $indice])) {
                                            if(isset($lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']])) {

                                                // if($lista[$key]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['id'] == $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['id']) {
                                                    $pedido = $lista[$key]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'] - ($lista[$key]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['estoque'] > 0 ? $lista[$key]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['estoque'] : 0);
                                                    $lista[$key]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['falta'] = $pedido;
                                                    
                                                    // $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'] = $pedido > 0 ? $pedido : 0;
                                                    // PROXIMO
                                                    if(isset($lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['producao'])) {    

                                                        // VERIFICA SE O ID DO PRODUTO É MESMO PARA REALIZARS A MUDANÇA
                                                        if($lista[$key]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['id'] == $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['id']) {
                                                            
                                                            if($item['pedido_original'] != $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido_original']) {
                                                                if($lista[$key]['status'] == 'polimento' && $item['id'] == 32) {
                                                                    // echo '<pre>111'; print_r($lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]); die;
                                                                    // echo '<pre>'; print_r($lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido']); die;
                                                                }

                                                                if(isset($lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['zerou'])) {
                                                                    $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'] += $pedido;
                                                                } else {
                                                                    $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['zerou'] = true;
                                                                    $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'] = 0;
                                                                    $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'] += $pedido;   
                                                                }

                                                                // $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'] -= $pedido;
                                                                $pedido = $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'];
                                                            } else {
                                                                // echo '<pre>'; print_r($item); die;
                                                                $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'] = $pedido > 0 ? $pedido : 0;
                                                                $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['falta'] = ($pedido > 0 ? $pedido : 0) - $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['estoque'];
                                                            }   
                                                        } else {
                                                            // VERIFICAR SE É MESMO STATUS, MESMO PRODUTO_MONTAGEM_ID E SE O PEDIDO É MAIOR
                                                            
                                                            if($lista[$key]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['produto_montagem_id'] == $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['produto_montagem_id']) {
                                                                
                                                                if(in_array($lista[$key + $indice]['status'], $lista[$key]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['producao'])) {
                                                                    
                                                                    if($lista[$key]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido_original'] != $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido_original']) {
                                                                        // echo '<pre>'; print_r($pedido); die;
                                                                        if(isset($lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['zerou'])) {
                                                                            $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'] += $pedido;
                                                                        } else {
                                                                            $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['zerou'] = true;
                                                                            $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'] = 0;
                                                                            $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'] += $pedido;   
                                                                        }
                                                                        $pedido = $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'];
                                                                    } else {
                                                                        
                                                                        // $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'] = $pedido > 0 ? $pedido : 0;
                                                                        if(isset($lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['zerou'])) {
                                                                            $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'] += $pedido;
                                                                        } else {
                                                                            $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['zerou'] = true;
                                                                            $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'] = 0;
                                                                            $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'] += $pedido;   
                                                                        }
                                                                        $pedido = $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['pedido'];
                                                                        $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['falta'] = ($pedido > 0 ? $pedido : 0) - $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['estoque'];
                                                                    }   
                                                                }
                                                            }
                                                        }                                    
                                                    }
                
                                                    $falta = $pedido - $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['estoque'];
                                                    $lista[$key + $indice]['produtos'][$cor][$keyProd][$item['produto_montagem_id']]['falta'] = $falta;
                                                    // $checkExists = false;
                                                // }
                                                
                                            } 
                                        }
                                        
                                        if($indice > 15) {
                                            $checkExists = false;
                                        }
                                        $indice++;
                                    }                       
                                }
                            }
                        }
                    }
                    
                }
            }
            // echo '<pre>'; print_r($lista[3]['produtos']['190']);
        }

        return $lista;
    }
}