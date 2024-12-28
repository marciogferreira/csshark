<?php
namespace App\Services;

use App\Models\AlunosModel as Model;

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
        
        if(isset($data['cpf']) && !empty($data['cpf'])) {
            $data['senha'] = md5($data['cpf']);
        }
        return $data;
    }
}