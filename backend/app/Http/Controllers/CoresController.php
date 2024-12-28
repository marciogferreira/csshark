<?php

namespace App\Http\Controllers;

use App\Services\CoresServices as Services;

class CoresController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }
}