<?php

namespace App\Http\Controllers;

use App\Services\VisitaServices as Services;
use Illuminate\Http\Request;

class VisitaController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }

    public function check(Request $request) {
        return $this->services->check($request);
    }
}