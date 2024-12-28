<?php

namespace App\Http\Controllers;

use App\Services\ClientsServices as Services;

class ClientsController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }
}