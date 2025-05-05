<?php

namespace App\Http\Controllers;

use App\Services\AlunosServices as Services;
use Illuminate\Http\Request;

class AlunosController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }

    public function updateStatus(Request $request, $id) {
        return $this->services->updateStatus($request, $id);
    }
}