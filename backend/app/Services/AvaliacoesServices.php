<?php
namespace App\Services;

use App\Models\AlunosModel;
use App\Models\Avaliacoes as Model;

class AvaliacoesServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->columnSearch = 'name';
        $this->model = $model;
    }

    public function index($request) {

        $params = $request->all();
        $aluno = AlunosModel::where('cpf', $params['cpf'])->first();
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
        ->where('aluno_id', $aluno->id)
        ->paginate(10);
        return $this->response($data);
    }

    public function beforeCreateData($params) {
        $aluno = AlunosModel::where('cpf', $this->user->email)->first();
        if($aluno) {
            $params['aluno_id'] = $aluno->id;
        }
        return $params;
    }

}