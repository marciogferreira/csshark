<?php

namespace App\Http\Controllers;

use App\Services\HistoricoProducaoServices as Services;

class HistoricoProducaoController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }
}