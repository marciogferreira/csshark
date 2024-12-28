<?php

namespace App\Http\Controllers;

use App\Services\OrdemProducaoServices as Services;
use Illuminate\Http\Request;

class OrdemProducaoController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }


    
    public function adicionarPedidoOp(Request $request) {
        return $this->services->adicionarPedidoOp($request);
    }
    public function removePedido($pedido_id) {
        return $this->services->removePedido($pedido_id);
    }
    

}