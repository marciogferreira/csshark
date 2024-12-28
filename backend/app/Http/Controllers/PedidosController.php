<?php

namespace App\Http\Controllers;

use App\Services\PedidosServices as Services;

class PedidosController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }

    public function consultOrdensWithStatus($status_producao_id, $ordem_producao_id) {
        $lista = $this->services->consultOrdensWithStatus($status_producao_id, $ordem_producao_id);
        return response()->json(['data' => $lista]);
    }
}