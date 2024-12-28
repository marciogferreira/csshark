<?php

namespace App\Http\Controllers;

use App\Services\FormaPagamentoServices as Services;

class FormaPagamentoController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }
}