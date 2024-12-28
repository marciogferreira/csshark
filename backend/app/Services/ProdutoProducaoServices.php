<?php
namespace App\Services;

use App\Models\ProdutoProducao as Model;

class ProdutoProducaoServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->columnSearch = 'name';
        $this->model = $model;
    }

    public function getList($produto_id) {
        $data = Model::where('produto_id', $produto_id)->get();

        foreach($data as $item) {
            $item->statusProducao;
            $item->status_producao_id = $item->statusProducao->id;
        }
        return response()->json(['data' => $data]);
    }

}