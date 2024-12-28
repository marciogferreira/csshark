<?php

namespace App\Http\Controllers;

use App\Services\ProdutoProducaoServices as Services;
use Illuminate\Http\Request;

class ProdutoProducaoController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }

    public function getList($produto_id) {
        return $this->services->getList($produto_id);
    }
    
}