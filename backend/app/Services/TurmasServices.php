<?php
namespace App\Services;

use App\Models\AlunosTurmasModel;
use App\Models\TurmasModel as Model;

class TurmasServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->columnSearch = 'nome';
        $this->indexOptions = [
            'value' => 'id',
            'label' => 'nome'
        ];
        $this->model = $model;
    }

    public function index($request) {
        $params = $request->all();
        $user = $request->user();

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
            $matricula = AlunosTurmasModel::where('turma_id', $item->id)
            ->where('aluno_id', $user->id)->first();
            if($matricula) {
                $item->matriculado = $matricula;
            } else {
                $item->matriculado = null;
            }
        }

        return $this->response($data);
    }

}