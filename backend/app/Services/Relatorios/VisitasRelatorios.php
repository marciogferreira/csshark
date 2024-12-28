<?php
namespace App\Services\Relatorios;

use App\Models\Visita as Model;
use App\Models\Client;
use App\Models\Colaborador;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VisitasRelatorios extends RelatoriosServices {
    
    public function config($params) {
        $this->model = new Model();
        
        if(isset($params['all_visitas']) && $params['all_visitas'] == 'true') {
            $data = DB::table('visitas as v')
            ->select('v.*')
            ->join('clients as cli', 'cli.id', 'v.cliente_id')
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

                if(isset($params['data_inicio']) && !empty($params['data_inicio'])) {
                    if(isset($params['data_inicio']) && !isset($params['data_fim'])) {
                        $query->where(DB::raw("DATE_FORMAT(v.data_visita, '%Y-%m-%d')"), '>=', $params['data_inicio']);
                    }
                    if(isset($params['data_inicio']) && isset($params['data_fim'])) {
                        $query->whereBetween(DB::raw("DATE_FORMAT(v.data_visita, '%Y-%m-%d')"), [$params['data_inicio'], $params['data_fim']]);
                    }
                }

                return $query;
            })
            ->get();
        } else {
            $data = $this->model->whereIn('id', $params['visitas'])
            ->orderBy('id')->get();
        }

        foreach($data as $item) {
            $item->vendedor = Colaborador::find($item->vendedor_id);
            $item->cliente = Client::find($item->cliente_id);
            $item->data_visita_f = Carbon::parse($item->data_visita)->format('d/m/Y');
        }
        
        $this->name = 'visitas';
        $this->view = 'relatorios.visitas';
        $this->data = $data;
    }

}