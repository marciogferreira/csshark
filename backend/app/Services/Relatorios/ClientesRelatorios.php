<?php
namespace App\Services\Relatorios;

use App\Models\HistoricosStatusPedido;
use App\Models\Client as Model;
use App\Models\Colaborador;
use App\Models\TabelaPreco;
use App\Models\Pedido;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ClientesRelatorios extends RelatoriosServices {
    
    public function config($params) {
        $this->model = new Model();
        

        if(isset($params['all_clientes']) && $params['all_clientes'] == 'true') {
            $data = DB::table('clients as cli')
            ->select('cli.*')
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
            ->get();
        } else {
            $data = $this->model->whereIn('id', $params['clientes'])->orderBy('razao_social')->get();
        }

        foreach($data as $item) {
            $item->vendedor = Colaborador::find($item->vendedor_id);
            $item->tabela = TabelaPreco::find($item->tabela_id);
            $item->data_ultimo_pedido = 'Nenhum pedido realizado';
            
            $pedido = Pedido::where('cliente_id', $item->id)->orderBy('codigo', 'desc')->first();
            if($pedido) {
                $item->data_ultimo_pedido = Carbon::parse($pedido->created_at)->format('d/m/Y');
            }
        }
        
        $this->name = 'clientes';
        $this->view = 'relatorios.clientes';
        $this->data = $data;
    }

}