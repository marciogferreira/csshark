<?php

namespace App\Http\Controllers;

use App\Services\ComissaoProducaoServices as Services;

class ComissaoProducaoController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }

   


}
