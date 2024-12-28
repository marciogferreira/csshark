<?php
namespace App\Services;

use App\Models\Client;
use App\Models\Colaborador;
use App\Models\Cor;
use App\Models\EstoqueProducao;
use App\Models\FormaPagamento;
use App\Models\HistoricosStatusPedido;
use App\Models\ItemPedido;
use App\Models\ItensTiposPagamentos;
use App\Models\OrdemProducao;
use App\Models\Pedido as Model;
use App\Models\Pedido;
use App\Models\Produto;
use App\Models\ProdutoMontagem;
use App\Models\ProdutoProducao;
use App\Models\StatusProducao;
use App\Models\Visita;

use App\Services\OrdemProducaoNegocios;
use Carbon\Carbon;
use Exception;
use stdClass;
use Illuminate\Support\Facades\DB;

class PedidosServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function show($id) {
        $data = $this->model->find($id);
        if(!empty($data->tabela_id)) {
            $data->tabela;
        }
        if(!empty($data->forma_pagamento_id)) {
            $data->forma_pagamento;
        }
        if(!empty($data->item_tipo_pagamento_id)) {
            $data->tipo_pagamento;
        }
        $data->cliente;
        $data->vendedor;
        $data->itens;
        $data->today = Carbon::now()->format('d/m/Y H:i:s');

        $total = 0;
        $peso_total = 0;
        foreach($data->itens as $item) {
            $item->produto;
            $total = $total + ($item->quantidade * $item->preco);
            $peso_total = $peso_total + ($item->quantidade * (float) $item->produto->peso);
        }
        $data->total = $total;
        $data->peso_total = $peso_total;
        $data->desconto_maior = '';
        
        if(!empty($data->item_tipo_pagamento_id)) {
            if($data->desconto > $data->tipo_pagamento->desconto) {
                $data->desconto_maior = 'true';
            }
        }
        $data->list_status_options = $data->listStatusOptions($data);        
        return response()->json(['data' => $data]);
    }
    
    public function index($request) {
        $params = $request->all();
        
        DB::enableQueryLog();
        $data = DB::table('pedidos as p')
            ->select('p.*')
            ->orderBy('id', 'desc')
            ->join('clients as c', 'c.id', 'p.cliente_id')
            ->join('colaboradors as v', 'v.id', 'p.vendedor_id')
            ->when($params, function($query, $params){
                if(isset($params['search']) && !empty($params['search'])) {
                    $query->whereRaw("(
                        c.razao_social like '%{$params['search']}%' or 
                        c.nome_fantasia like '%{$params['search']}%' or                       
                        c.cnpj like '%{$params['search']}%' or 
                        c.cpf like '%{$params['search']}%' or 
                        c.bairro like '%{$params['search']}%' or 
                        c.cidade like '%{$params['search']}%' or 
                        c.uf like '%{$params['search']}%' or 
                        c.cep like '%{$params['search']}%' or 
                        v.name like '%{$params['search']}%' or
                        p.codigo like '%{$params['search']}%'
                    )");
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

                if(isset($params['status_list']) && !empty($params['status_list'])) {
                    $query->whereIn('p.status', $params['status_list']);
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

                if(isset($params['ordem_producao_id']) && !empty($params['ordem_producao_id'])) {
                    $query->where('p.ordem_producao_id', $params['ordem_producao_id']);
                }               
                
                return $query;
            })
        ->whereIn('tipo', ['P', 'R'])
        ->paginate(10);
        
        foreach($data as $item) {
            $item->vendedor = Colaborador::find($item->vendedor_id);
            $item->cliente = Client::find($item->cliente_id);
            $item->data = Carbon::parse($item->created_at)->format('d/m/Y H:i:s');

            if($item->forma_pagamento_id) {
                $item->forma_pagamento = FormaPagamento::find($item->forma_pagamento_id);
            }
            if($item->item_tipo_pagamento_id) {
                $item->item_tipo_pagamento = ItensTiposPagamentos::find($item->item_tipo_pagamento_id);
            }

            $item->itens = ItemPedido::where('pedido_id', $item->id)->get();
            $total = 0;
            foreach($item->itens as $produto) {
                $produto->produto;
                $total = $total + NegociosServices::getCalcularDescontoPorPedidoId($item->id, $produto->id);
                // $total = $total + ($produto->quantidade * $produto->preco);
            }

            $item->desconto_maior = '';
        
            if(!empty($item->item_tipo_pagamento_id)) {
                if(isset($item->item_tipo_pagamento->desconto)) {
                    if($item->desconto > $item->item_tipo_pagamento->desconto) {
                        $item->desconto_maior = 'true';
                    }
                }
            }

            // $item->total = $total - ($total * $item->desconto / 100);
            $item->total = $total;
        }

        return $this->response($data);

    }

    public function sanitizeDataCreate($data) {
        $data->cliente;
        return $data;
    }

    public function beforeCreateData($data){

        $maxCodigo = $this->model->selectRaw("max(codigo) as max")
            ->get()->first();
        
        if($maxCodigo) {
            $codigoMax = (int) $maxCodigo->max;
            $codigoMax = $codigoMax + 1;
            $codigoMax = str_pad($codigoMax, 4, '0', STR_PAD_LEFT);
            $data['codigo'] = $codigoMax;
        } else {
            $data['codigo'] = '0001';
        }

        $cliente = Client::find($data['cliente_id']);
        if(empty($cliente->tabela_id) || $cliente->tabela_id == 0 || $cliente->tabela_id == '0') {
            throw new Exception("Cliente não possui Tabela de Preço Informada.");
        }
        
        $data['tabela_id'] = $cliente->tabela_id;
        $data['vendedor_id'] = $cliente->vendedor_id;
        
        if(isset($data['forma_pagamento_id']) && !empty($data['forma_pagamento_id'])) {
            $data['status'] = Model::STATUS_AGUARDANDO;
        } else {
            $data['status'] = Model::STATUS_ABERTO;
        }
        // echo '<pre>'; print_r($data); die;
        return $data;
    }

    public function afterCreateData($data) {

        // QUANDO O PEDIDO É CRIADO - CRIA UM HISTORICO DE STATUS
        if($data->status == Model::STATUS_ABERTO) {
            HistoricosStatusServices::LancarSatus($data->id, $data->status, '');
        }
        
        
    }

    public function beforeUpdateData($data){
        
        if($data['model']->status === 'finished') {
            throw new Exception("Este pedido já se encontra finalizado e não pode ser editado.");
        }
        
        // if(isset($data['status']) && $data['status'] == '5') {
        //     $data['status'] = Model::STATUS_APROVADO;
        // } else {
        //     if(isset($data['status']) && $data['status'] == '3') {
        //         $data['status'] = Model::STATUS_FINALIZADO;
        //     } else {
        //         if(isset($params['forma_pagamento_id']) && !empty($params['forma_pagamento_id'])) {
        //             $data['status'] = Model::STATUS_AGUARDANDO;
        //         } else {
        //             $data['status'] = Model::STATUS_ABERTO;
        //         }
        //     }
        // }
        
        if(isset($data['forma_pagamento_id']) && !empty($data['forma_pagamento_id'])) {
            $data['status'] = Model::STATUS_AGUARDANDO;
        }

        $observacao = '';
        if(isset($data['observacao_status'])) {
            $observacao = $data['observacao_status'];
            $data['observacao'] = $data['observacao_status'];
        }
        HistoricosStatusServices::LancarSatus($data['id'], $data['status'], $observacao);
        
        // echo '<pre>'; print_r($data); die;
        return $data;
    }

    public function beforeDataDelete($data) {
        if($data->status !== 'open') {
            throw new Exception("Não foi possível remover este pedido.");
        }

        
        
        ItemPedido::where('pedido_id', $data->id)->delete();
        HistoricosStatusPedido::where('pedido_id', $data->id)->delete();
    }

    public function consultOrdensWithStatus($status_producao_id, $ordem_producao_id) {

        $listaProdutos = [];
        $listaSemiProdutos = [];
        $listaEstoque = [];
        $listaStatus = [];
    
        $this->model = new OrdemProducao();
        $data = $this->model->find($ordem_producao_id);
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
    
        $listt = [];
        // ALIMENTA ESTOQUE
        foreach($listaProdutos as $produto) {
            $itensMontagem = ProdutoMontagem::where('produto_id', $produto['id'])->get();
            
            foreach($itensMontagem as $itemMontagem) {
                $estoques = EstoqueProducao::where('produto_id', $itemMontagem->produto_montagem_id)
                    ->get();
                foreach($estoques as $estoque) { 
                    if(!isset($listaEstoque[$estoque->produto_id.$estoque->status_producao_id])) {
                        $listaEstoque[$estoque->produto_id.$estoque->status_producao_id.$estoque->cor_id] = $estoque->toArray();
                    } 
    
                    $listt[] = $estoque->toArray();
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
                
        
        $statusProducao = StatusProducao::find($status_producao_id);
        foreach($listaStatus as $key => $item) {
            if($item['nome'] != $statusProducao->nome) {
                unset($listaStatus[$key]);
            }
        }
        // echo '<pre>'; print_r($listaStatus); die;
        $result = [];
        foreach($listaStatus as $item) {
            $result = $item;
        }
        
        $results = [];
        foreach($result['itens'] as $item) {
            $results[] = $item;
        }
        
        
        // return produtos agrupados
        return $results;
    }


}