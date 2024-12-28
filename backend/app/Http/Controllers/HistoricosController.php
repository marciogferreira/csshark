<?php

namespace App\Http\Controllers;

use App\Services\HistoricosStatusServices as Services;

class HistoricosController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }
}