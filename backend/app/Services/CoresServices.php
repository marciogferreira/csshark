<?php
namespace App\Services;

use App\Models\Cor as Model;

class CoresServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->columnSearch = 'name';
        $this->model = $model;
    }

}