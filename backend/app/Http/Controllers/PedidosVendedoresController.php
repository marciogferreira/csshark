<?php

namespace App\Http\Controllers;

use App\Services\PedidosVendedoresServices as Services;

class PedidosVendedoresController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }
}