<?php

namespace App\Http\Controllers;

use App\Services\TreinosServices as Services;

class TreinosController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }
}