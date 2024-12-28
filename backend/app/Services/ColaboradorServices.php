<?php
namespace App\Services;

use App\Models\Colaborador as Model;
use Illuminate\Support\Facades\DB;

class ColaboradorServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->indexOptions = [
            'label' => 'name',
            'value' => 'id',
        ];
    }


    public function index($request) {
        $params = $request->all();
        $data = DB::table('colaboradors as c')
        ->select('c.*', 'car.name as cargo_descricao')
        ->join('cargos as car', 'car.id', 'c.cargo_id')
        ->when($params, function($query, $params){
            if(isset($params['search'])) {
                $query->whereRaw("(
                    c.name like '%{$params['search']}%' or
                    car.name like '%{$params['search']}%' 
                )");
            }

            return $query;
        })
        ->paginate(10);
        
        return $this->response($data);
    }

    public function options($params) {
        $list = $this->model
        ->when($params, function($query, $params) {
            if(isset($params['setor']) && $params['setor'] == 'producao') {
                return $query->whereIn('cargo_id', [4, 5, 6, 7, 9, 10, 11, 12]); // Setor de produção
            }
        })
        ->get();
        $res = [];
        $value = $this->indexOptions['value'];
        $label = $this->indexOptions['label'];
        
        foreach($list as $item) {
            $res[] = [
                'value' => $item->$value,
                'label' => $item->$label,
            ];
        }
        
        return response(['data' => $res]);
    }

    function beforeCreateData($data) {
        $data['cargo_id'] = 1;
        return $data;
    }

    

}