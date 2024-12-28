<?php

namespace App\Http\Controllers;

use App\Services\ModalidadesServices as Services;

class ModalidadesController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }
}