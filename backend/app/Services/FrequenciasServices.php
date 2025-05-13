<?php
namespace App\Services;

use App\Models\Frequencias as Model;
use Carbon\Carbon;

class FrequenciasServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->columnSearch = 'name';
        $this->model = $model;
    }

    public function index($request) {
        $params = $request->all();

        $data = $this->model->when($params, function($query, $params) {
            if(isset($params['search'])) {
                $query->where($this->columnSearch, 'like', "%{$params['search']}%");
            }
            return $query;
        })
        ->when($this->orderBy, function($query, $orderBy) {
            if($orderBy === 'desc') {
                return $query->orderBy('id', 'desc');
            }
        })
        ->paginate(10);

        foreach($data as $item) {
            $item->aluno;
        }
        
        return $this->response($data);
    }

    public function beforeCreateData($data) {
        $data['aluno_id'] = $this->user->id;
        $data['data'] = Carbon::now()->setTimezone('America/Sao_Paulo')->format('Y-m-d');
        $data['hora'] = Carbon::now()->setTimezone('America/Sao_Paulo')->format('H:i:s');
        return $data;
    }

}