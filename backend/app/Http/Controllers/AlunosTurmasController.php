<?php

namespace App\Http\Controllers;

use App\Services\AlunosTurmasServices as Services;

class AlunosTurmasController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }
}