<?php

namespace App\Http\Controllers;

use App\Services\TurmasServices as Services;

class TurmasController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }
}