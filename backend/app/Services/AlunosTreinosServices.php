<?php
namespace App\Services;

use App\Models\AlunosTreinosModel as Model;

class AlunosTreinosServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->columnSearch = 'nome';
        $this->model = $model;
    }

}