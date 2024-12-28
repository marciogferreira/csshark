<?php

namespace App\Http\Controllers;

use App\Services\ClientsVendedorServices as Services;

class ClientsVendedorController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }
}