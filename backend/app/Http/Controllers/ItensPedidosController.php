<?php

namespace App\Http\Controllers;

use App\Services\ItensPedidosServices as Services;
use Illuminate\Http\Request;

class ItensPedidosController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }

    public function agrupar(Request $request) {
        return $this->services->listAgrupados($request->all());
    }
}