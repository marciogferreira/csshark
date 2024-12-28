<?php

namespace App\Http\Controllers;

use App\Services\ColaboradorServices as Services;

class ColaboradorController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }

}