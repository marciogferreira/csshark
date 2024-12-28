<?php
namespace App\Services;

use App\Models\HistoricoProducao as Model;

class HistoricoProducaoServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

}