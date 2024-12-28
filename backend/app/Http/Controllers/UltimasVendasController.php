<?php

namespace App\Http\Controllers;

use App\Services\UltimasVendasServices as Services;

class UltimasVendasController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }
}