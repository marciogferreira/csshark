<?php
namespace App\Services;

use App\Models\TreinosModel as Model;

class TreinosServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->columnSearch = 'nome';
        $this->model = $model;
    }

}