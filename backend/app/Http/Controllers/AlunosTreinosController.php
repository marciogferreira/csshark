<?php

namespace App\Http\Controllers;

use App\Services\AlunosTreinosServices as Services;

class AlunosTreinosController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }

    public function fichaByAluno($email) {
        return $this->services->fichaByAluno($email);
    }
}
