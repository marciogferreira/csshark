<?php

namespace App\Http\Controllers;

use App\Services\VisitaVendedoresServices as Services;
use Illuminate\Http\Request;

class VisitaVendedoresController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }

    public function check(Request $request) {
        return $this->services->check($request);
    }
}