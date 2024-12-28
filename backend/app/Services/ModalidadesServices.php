<?php
namespace App\Services;

use App\Models\ModalidadesModel as Model;

class ModalidadesServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->columnSearch = 'nome';
        $this->indexOptions = [
            'value' => 'id',
            'label' => 'nome'
        ];
        $this->model = $model;
    }

}