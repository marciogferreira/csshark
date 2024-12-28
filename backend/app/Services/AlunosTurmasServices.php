<?php
namespace App\Services;

use App\Models\AlunosTurmasModel as Model;
use Carbon\Carbon;

class AlunosTurmasServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->columnSearch = 'nome';
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
            $item->turma;
            $item->data_inicio = Carbon::parse($item->data_inicio)->format('d/m/Y');
        }

        return $this->response($data);
    }

}