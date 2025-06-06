<?php
namespace App\Services;

use App\Models\AlunosModel;
use App\Models\TreinosModel as Model;
use App\Models\TreinosModel;
use Illuminate\Database\Eloquent\Builder;
class TreinosServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->columnSearch = 'nome';
        $this->model = $model;
    }

    public function index($request) {
        $params = $request->all();

        $search = isset($params['search']) ? $params['search'] : '';
        
        $data = TreinosModel::whereHas('aluno', function(Builder $query) use ($search) {
            // $query->where('nome', 'like', "%{$search}%");
            $query->whereRaw("
                    (
                    nome like '%{$search}%' 
                        OR 
                    cpf like '%{$search}%'
                    )
                ");
        })
        ->when($params, function($query, $params) {
            if(isset($params['search'])) {
                // $query->where($this->columnSearch, 'like', "%{$params['search']}%");
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

    public function show($id) {
        $data = $this->model->find($id);
        
        return response()->json(['data' => $data]);
    }

    public function beforeCreateData($params) {
        $params['data'] = json_encode($params['data']);
        return $params;
    }

    public function beforeUpdateData($params) {
        $params['data'] = json_encode($params['data']);
        return $params;
    }

}