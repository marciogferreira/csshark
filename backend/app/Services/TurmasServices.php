<?php
namespace App\Services;

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

}