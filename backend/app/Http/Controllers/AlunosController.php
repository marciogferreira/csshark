<?php

namespace App\Http\Controllers;

use App\Services\AlunosServices as Services;

class AlunosController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }
}