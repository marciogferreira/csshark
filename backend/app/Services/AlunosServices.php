<?php
namespace App\Services;

use App\Models\AlunosModel as Model;
use Exception;

class AlunosServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->columnSearch = 'nome';
        $this->indexOptions = [
            'value' => 'id',
            'label' => 'nome'
        ];
        $this->model = $model;
    }


    function beforeCreateData($data) {
        
        $aluno = Model::where('cpf', $data['cpf'])->first();
        if($aluno) {
            throw new Exception('Já existe um(a) aluno(a) cadastrado(a) com esse CPF. Por favor, dirija-se a recepção do Box.');
        }

        if(isset($data['cpf']) && !empty($data['cpf'])) {
            $data['senha'] = md5($data['cpf']);
        }
        return $data;
    }



}