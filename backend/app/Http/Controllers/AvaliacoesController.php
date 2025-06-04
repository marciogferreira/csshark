<?php

namespace App\Http\Controllers;

use App\Services\AvaliacoesServices as Services;

class AvaliacoesController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }
}