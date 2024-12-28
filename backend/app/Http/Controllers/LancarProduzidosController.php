<?php

namespace App\Http\Controllers;

use App\Services\LancarProduzidosServices as Services;

class LancarProduzidosController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }

}