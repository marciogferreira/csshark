<?php

namespace App\Http\Controllers;

use App\Services\TabelaPrecosServices as Services;

class TabelaPrecosController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }
}