<?php
namespace App\Http\Controllers;

use App\Services\UsersServices as Services;

class UsersController extends ApiController {

    public function __construct(Services $services) {
        $this->services = $services;
    }
}