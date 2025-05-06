<?php

namespace App\Http\Controllers;

use App\Services\ModelosTreinosServices as Services;

class ModelosTreinosController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }
}