<?php
namespace App\Services;

use App\Models\Client as Model;
use App\Models\Client;
use App\Models\Colaborador;
use App\Models\Pedido;
use App\Models\Visita;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class UltimasVendasServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
    
    public function index($request) {
        $params = $request->all();
        
        if(isset($params['ordem']) && $params['ordem'] == 'true') {
            $params['ordem'] = 'desc';
        } else {
            $params['ordem'] = 'asc';
        }

       

        // $data = DB::table('clients as cli')
        // ->select(
        //     'cli.*',
        //     DB::raw("(SELECT count(1) FROM pedidos WHERE cliente_id = cli.id GROUP BY cliente_id) as quant_pedidos")
        // )
        // ->join('colaboradors as c', 'c.id', 'cli.vendedor_id')
        // ->leftJoin('pedidos as p', 'p.cliente_id', 'cli.id')
        // ->leftJoin('visitas as v', 'v.cliente_id', 'cli.id')
        // ->when($params, function($query, $params) {
        //     if(isset($params['search'])) {
        //         $query->orWhere('cli.razao_social', 'like', "%{$params['search']}%")
        //         ->orWhere('cli.nome_fantasia', 'like', "%{$params['search']}%")
        //         ->orWhere(DB::Raw("REPLACE(REPLACE(REPLACE(cli.cpf,'.', ''),'-', ''),'/', '')"), 'like', "%{$params['search']}%")
        //         ->orWhere(DB::Raw("REPLACE(REPLACE(REPLACE(cli.cnpj,'.', ''),'-', ''),'/', '')"), 'like', "%{$params['search']}%")
        //         // ->orWhere('cnpj', 'like', "%{$params['search']}%")
        //         // ->orWhere('cpf', 'like', "%{$params['search']}%")
        //         ->orWhere('cli.logradouro', 'like', "%{$params['search']}%")
        //         ->orWhere('cli.bairro', 'like', "%{$params['search']}%")
        //         ->orWhere('cli.cidade', 'like', "%{$params['search']}%")
        //         ->orWhere('cli.cep', 'like', "%{$params['search']}%")
        //         ->orWhere('cli.fone', 'like', "%{$params['search']}%")
        //         ->orWhere('cli.email', 'like', "%{$params['search']}%");
        //     }

        //     if(isset($params['bairro'])) {
        //         $query->where('cli.bairro', 'like', "%{$params['bairro']}%");
        //     }

        //     if(isset($params['cidade'])) {
        //         $query->where('cli.cidade', 'like', "%{$params['cidade']}%");
        //     }

        //     if(isset($params['estado'])) {
        //         $query->where('cli.uf', 'like', "%{$params['estado']}%");
        //     }

        //     if(isset($params['situacao'])) {
        //         $query->where('cli.situacao', $params['situacao']);
        //     }

        //     if(isset($params['vendedor'])) {
        //         $query->where('c.name', 'like', "%{$params['vendedor']}%");
        //     }

        //     return $query;
        // })
        // ->when($params, function($query, $params) {
            
        //     if(isset($params['has_tabela'])) {
        //         if($params['has_tabela'] == 'true') {
        //             $query->whereNotNull('cli.tabela_id');
        //         } else {
        //             $query->where('cli.tabela_id', 0);
        //         }
        //     }
            
        //     return $query;
        // })
        // ->groupBy(
        //     'id',
        //     'razao_social',
        //     'nome_fantasia',
        //     'cnpj',
        //     'cpf',
        //     'logradouro',
        //     'numero',
        //     'complemento',
        //     'bairro',
        //     'cidade',
        //     'uf',
        //     'cep',
        //     'fone',
        //     'celular',
        //     'email',
        //     'vendedor_id',
        //     'tabela_id',
        //     'telefone',
        //     'observacao',
        //     'situacao',
        //     'nome_contato',
        //     'email_contato',
        //     'telefone_contato'
        // )
        // ->orderBy('quant_pedidos', $params['ordem'])
        // ->paginate(10);


        $data = DB::table('clients as cli')
        ->select(
            'cli.*'
        )
        ->join('colaboradors as c', 'c.id', 'cli.vendedor_id')
        ->when($params, function($query, $params) {
            if(isset($params['search'])) {
                $query->orWhere('cli.razao_social', 'like', "%{$params['search']}%")
                ->orWhere('cli.nome_fantasia', 'like', "%{$params['search']}%")
                ->orWhere(DB::Raw("REPLACE(REPLACE(REPLACE(cli.cpf,'.', ''),'-', ''),'/', '')"), 'like', "%{$params['search']}%")
                ->orWhere(DB::Raw("REPLACE(REPLACE(REPLACE(cli.cnpj,'.', ''),'-', ''),'/', '')"), 'like', "%{$params['search']}%")
                // ->orWhere('cnpj', 'like', "%{$params['search']}%")
                // ->orWhere('cpf', 'like', "%{$params['search']}%")
                ->orWhere('cli.logradouro', 'like', "%{$params['search']}%")
                ->orWhere('cli.bairro', 'like', "%{$params['search']}%")
                ->orWhere('cli.cidade', 'like', "%{$params['search']}%")
                ->orWhere('cli.cep', 'like', "%{$params['search']}%")
                ->orWhere('cli.fone', 'like', "%{$params['search']}%")
                ->orWhere('cli.email', 'like', "%{$params['search']}%");
            }

            if(isset($params['bairro'])) {
                $query->where('cli.bairro', 'like', "%{$params['bairro']}%");
            }

            if(isset($params['cidade'])) {
                $query->where('cli.cidade', 'like', "%{$params['cidade']}%");
            }

            if(isset($params['estado'])) {
                $query->where('cli.uf', 'like', "%{$params['estado']}%");
            }

            if(isset($params['situacao'])) {
                $query->where('cli.situacao', $params['situacao']);
            }

            if(isset($params['vendedor'])) {
                $query->where('c.name', 'like', "%{$params['vendedor']}%");
            }

            return $query;
        })
        ->when($params, function($query, $params) {
            
            if(isset($params['has_tabela'])) {
                if($params['has_tabela'] == 'true') {
                    $query->whereNotNull('cli.tabela_id');
                } else {
                    $query->where('cli.tabela_id', 0);
                }
            }
            
            return $query;
        })
        ->paginate(10);
        $result = [];
        $count = 0;
        foreach($data as $item) {

            $item->vendedor = Colaborador::find($item->vendedor_id);
            $item->visita = Visita::where('cliente_id', $item->id)
                // ->whereNotNull('hora_check_out')
                ->orderBy('data_visita', 'desc')
                ->first();

            if($item->visita) {
                $dateVisita = Carbon::parse($item->visita->created_at);
                $item->visita->data = $dateVisita->format('d/m/Y');
                $item->visita->dias = Carbon::now()->setTimezone('America/Sao_Paulo')->diffInDays($dateVisita);
            }
            $item->venda = Pedido::where('cliente_id', $item->id)
            ->orderBy('id', 'desc')
            ->first();
            if($item->venda) {
                $dateVenda = Carbon::parse($item->venda->created_at);
                $item->venda->data = $dateVenda->format('d/m/Y');
                $item->venda->dias = Carbon::now()->setTimezone('America/Sao_Paulo')->diffInDays($dateVenda);
            }
            
            $vendaDias = 0;
            $visitaDias = 0;
            $count++;
            if(isset($item->venda->dias)) {
                $vendaDias = $item->venda->dias;
            }
            if(isset($item->visita->dias)) {
                $visitaDias = $item->visita->dias;
            }
            
            $result[intval($vendaDias) + intval($visitaDias)] = $item;
        }

        if($params['ordem'] == 'asc') {
            ksort($result);
        } else {
            krsort($result);
        }
        
        $listResults = [];

        foreach($result as $item) {
            $listResults[] = $item;
        }

        return $this->response(['data' => $data, 'list' => $listResults]);
    }

}