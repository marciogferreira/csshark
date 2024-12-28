<?php
namespace App\Services;

use App\Models\Cargos as Model;

class CargosServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->columnSearch = 'name';
        $this->model = $model;
    }

}