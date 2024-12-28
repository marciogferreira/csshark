<?php
namespace App\Services;

use App\Models\Client;
use App\Models\Visita as Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class VisitaVendedoresServices extends BaseServices {
    
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
        $params = $request->all();
        $data = $this->model
        ->where('vendedor_id', $request->user()->colaborador_id)
        ->orderBy('id', 'desc')->paginate(10);

        $data = DB::table('visitas as v')
        ->select('v.*')
        ->join('clients as c', 'c.id', 'v.cliente_id')
        ->join('colaboradors as ven', 'ven.id', 'v.vendedor_id')
        ->when($params, function($query, $params) {
            if(isset($params['search'])) {
                $query->whereRaw("
                    c.razao_social like '%{$params['search']}%'
                ");
            }
            return $query;
            
        })
        
        ->paginate(10);

        foreach($data as $item) {
            $item->cliente = Client::find($item->cliente_id);
            $item->data_visita_f = Carbon::parse($item->data_visita)->format('d/m/Y');
        }
        return $this->response($data);
    }

    public function beforeCreateData($data){

        $data['data_visita'] = Carbon::now()->setTimezone('America/Sao_Paulo')->format('Y-m-d');
        $data['hora_visita'] = Carbon::now()->setTimezone('America/Sao_Paulo')->format('H:i');
        
        $cliente = Client::find($data['cliente_id']);
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