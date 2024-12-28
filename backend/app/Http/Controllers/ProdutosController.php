<?php

namespace App\Http\Controllers;

use App\Services\ProdutosServices as Services;
use Illuminate\Http\Request;

class ProdutosController extends ApiController {

    protected $services = null;

    public function __construct(Services $services) {
        $this->services = $services;
    }

    public function listProdutosByPorcentagem($id) {
        return $this->services->listProdutosByPorcentagem($id);
    }


    public function getImages($produto_id)
    {
        return $this->services->getImages($produto_id);
    }

    public function createImages(Request $request)
    {
        return $this->services->createImages($request);
    }

    public function deleteImages($produto_id)
    {
        return $this->services->deleteImages($produto_id);
    }


    

}