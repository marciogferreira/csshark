<?php

namespace App\Http\Controllers;

use App\Services\FornecedoresServices as Services;

class FornecedoresController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }
}