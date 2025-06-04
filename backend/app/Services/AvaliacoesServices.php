<?php
namespace App\Services;

use App\Models\Avaliacoes as Model;

class AvaliacoesServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->columnSearch = 'name';
        $this->model = $model;
    }

}