<?php

namespace App\Http\Controllers;

use App\Services\StatusProducaoServices as Services;

class StatusProducaoController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }

    

    public function listaPorOrdem($ordem_id) {
        return $this->services->listaPorOrdem($ordem_id);
    }
}