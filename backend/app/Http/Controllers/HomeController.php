<?php
App\Http\Controllers;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController{
    
    public function index(Request $request) {
        echo '<pre>'; print_r("dsada"); die;
    }
}