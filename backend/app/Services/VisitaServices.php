<?php
namespace App\Services;

use App\Models\Client;
use App\Models\Colaborador;
use App\Models\Visita as Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VisitaServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function show($id) {
        $data = $this->model->find($id);
        $data->cliente;

        return $this->response(['data' => $data]);
    }

    public function index($request) {

        $asc = 'asc';

        $params = $request->all();
        if(isset($params['asc']) && $params['asc'] == 'true') {
            $asc = 'desc';
        }
        $data = DB::table('visitas as v')
        ->select('v.*')
        ->join('clients as c', 'c.id', 'v.cliente_id')
        ->join('colaboradors as ven', 'ven.id', 'v.vendedor_id')
        ->when($params, function($query, $params) {
            
            
            if(isset($params['bairro']) && !empty($params['bairro'])) {
                $query->where('c.bairro', 'like', "%{$params['bairro']}%");
            }
            if(isset($params['estado']) && !empty($params['estado'])) {
                $query->where('c.estado', 'like', "%{$params['estado']}%");
            }
            if(isset($params['vendedor']) && !empty($params['vendedor'])) {
                $query->where('ven.name', 'like', "%{$params['vendedor']}%");
            }

            if(isset($params['cliente_id']) && !empty($params['cliente_id'])) {
                $query->where('v.cliente_id', $params['cliente_id']);
            }
            

            if(isset($params['situacao'])) {
                $query->where('c.situacao', $params['situacao']);
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
        ->orderBy('v.id', $asc)
        ->paginate(10);

        foreach($data as $item) {
            $item->cliente = Client::find($item->cliente_id);
            $item->vendedor = Colaborador::find($item->vendedor_id);
            $item->data_visita_f = Carbon::parse($item->data_visita)->format('d/m/Y');
        }
        return $this->response($data);
    }

    public function beforeCreateData($data){
        $cliente = Client::find($data['cliente_id']);
        $data['tabela_id'] = $cliente->tabela_id;
        $data['vendedor_id'] = $cliente->vendedor_id;
        
        // echo '<pre>'; print_r($data); die;
        return $data;
    }

    public function check($request) {
        $params = $request->all();
        $visita = $this->model->find($params['id']);
        
        if($visita) {
            if($params['type'] == 'checkin') {
                $visita->update([
                    'lat_check_in' => $params['lat_check_in'],
                    'lng_check_in' => $params['lng_check_in'],
                    'hora_check_in' => Carbon::now()->setTimezone('America/Sao_Paulo')->format('H:s:i'),
                ]);
            }
    
            if($params['type'] == 'checkout') {
                $visita->update([
                    'lat_check_out' => $params['lat_check_out'],
                    'lng_check_out' => $params['lng_check_out'],
                    'hora_check_out' => Carbon::now()->setTimezone('America/Sao_Paulo')->format('H:s:i'),
                ]);
            }
        }
    }
}