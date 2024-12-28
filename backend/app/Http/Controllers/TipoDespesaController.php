<?php

namespace App\Http\Controllers;

use App\Services\TipoDespesaServices as Services;

class TipoDespesaController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }
}