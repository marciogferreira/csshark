<?php
namespace App\Services;

use App\Models\EstoqueProducao as Model;
use App\Models\EstoqueProducao;
use App\Models\Produto;
use App\Models\StatusProducao;
use Exception;
use Illuminate\Support\Facades\DB;

class EstoqueProducaoServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->indexOptions = [
            'label' => 'name',
            'value' => 'id',
        ];
    }

    public function index($request) {
        $params = $request->all();

        $data = $this->model->when($params, function($query, $params) {
            if(isset($params['search'])) {
                $query->where($this->columnSearch, 'like', "%{$params['search']}%");
            }
            return $query;
        })
        ->when($this->orderBy, function($query, $orderBy) {
            if($orderBy === 'desc') {
                return $query->orderBy('id', 'desc');
            }
        })
        ->paginate(10);

        foreach($data as $item) {
            $item->produto;
            $item->status;
            $item->is_finally = true;
            $statusProducao = StatusProducao::where('status_producao_id', $item->status->id)->get()->first();
            if($statusProducao) {
                $item->is_finally = false;
            }
        }

        return $this->response($data);
    }
    
    public function remanejarProduto($params) {
        try {
            DB::beginTransaction();
            $EstoqueProducao = EstoqueProducao::where('id', $params['estoque_producao_id'])
            ->where('produto_id', $params['produto_id'])->get()->first();
            if($EstoqueProducao) {
                $produto = Produto::find($EstoqueProducao->produto_id);
                if($produto) {
                    $produto->quantidade += $EstoqueProducao->quantidade;
                    $produto->save();

                    $EstoqueProducao->quantidade = 0;
                    $EstoqueProducao->save();
                }
            }
            DB::commit();
            return $this->response(['message' => 'Estoque Remanejado com Sucesso.']);
        } catch(Exception $e) {
            DB::rollBack();
            return $this->response(['message' => $e->getMessage()], 500);
        }
    }

}