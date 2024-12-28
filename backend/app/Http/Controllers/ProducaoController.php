<?php

namespace App\Http\Controllers;

use App\Services\ProducaoServices as Services;

class ProducaoController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }
}