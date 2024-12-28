<?php

namespace App\Http\Controllers;

use App\Services\EstoqueProducaoServices as Services;
use Illuminate\Http\Request;

class EstoqueProducaoController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }

   
    public function remanejarProduto(Request $request) {
        return $this->services->remanejarProduto($request->all());
    }


}
