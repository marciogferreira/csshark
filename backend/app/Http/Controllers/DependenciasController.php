<?php

namespace App\Http\Controllers;

use App\Services\DependenciasServices as Services;

class DependenciasController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }

    public function listProdutos($id) {
        return $this->services->listProdutos($id);
    }
}