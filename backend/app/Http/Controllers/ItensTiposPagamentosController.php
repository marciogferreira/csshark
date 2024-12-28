<?php

namespace App\Http\Controllers;

use App\Services\ItensTiposPagamentosServices as Services;

class ItensTiposPagamentosController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }
}