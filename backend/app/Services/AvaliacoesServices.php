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

     public function beforeCreateData($params) {
        $aluno = AlunosModel::where('cpf', $this->user->email)->first();
        if($aluno) {
            $params['aluno_id'] = $aluno->id;
        }
        return $params;
    }

}