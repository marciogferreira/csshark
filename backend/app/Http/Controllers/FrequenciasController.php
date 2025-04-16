<?php

namespace App\Http\Controllers;

use App\Services\FrequenciasServices as Services;

class FrequenciasController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }
}