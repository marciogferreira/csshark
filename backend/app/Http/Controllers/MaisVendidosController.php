<?php

namespace App\Http\Controllers;

use App\Services\MaisVendidosServices as Services;

class MaisVendidosController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }
}