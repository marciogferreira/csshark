<?php
namespace App\Services;

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
        $data = TreinosModel::with('aluno')
        ->whereHas('aluno', function(Builder $query) use ($search) {
            $query->where('nome', 'like', "%{$search}%");
        })
        ->when($params, function($query, $params) {
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

    public function beforeCreateData($params) {
        $params['data'] = json_encode($params['data']);
        return $params;
    }

    public function beforeUpdateData($params) {
        $params['data'] = json_encode($params['data']);
        return $params;
    }

}