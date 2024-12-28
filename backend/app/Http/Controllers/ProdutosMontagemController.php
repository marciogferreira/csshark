<?php

namespace App\Http\Controllers;

use App\Services\ProdutosMontagemServices as Services;

class ProdutosMontagemController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }

    public function listProdutos($id) {
        return $this->services->listProdutos($id);
    }
}