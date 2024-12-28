<?php
namespace App\Services;

use App\Models\Produto as Model;
use App\Models\TabelaPreco;
use Exception;
use App\Models\ProdutosImages;
use Illuminate\Support\Facades\DB;

class ProdutosServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->columnSearch = 'titulo';
        $this->model = $model;
    }

    public function index($request) {
        $params = $request->all();
        if(isset($params['search'])) {
            $data = $this->model->where('titulo', 'like', "%{$params['search']}%")->paginate(10);
        } else {
            $data = $this->model->paginate(10);
        }

        foreach($data as $item) {
            $image = ProdutosImages::where('produto_id', $item->id)->get()->first();
            if($image) {
                $item->image = url('/images').'/'.$image->path;
                // $path = public_path('/images').'/'.$image->path;
                // $type = pathinfo($path, PATHINFO_EXTENSION);
                // $data = file_get_contents($path);
                // $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                // $item->image = "".$base64."";
            }
        }
        return $this->response($data);
    }

    public function options($params) {
        $list = $this->model->select('id as value', 'titulo as label')
        ->when($params, function($query, $params) {
            if(isset($params['tipo'])) {
                return $query->where('tipo', $params['tipo']);
            }
        })
        ->get();
        return $this->response(['data' => $list]);
    }

    public function listProdutosByPorcentagem($id) {
        $list = $this->model->paginate(10);
        $data = TabelaPreco::find($id);

        foreach($list as $item) {
            $item->preco_total = $item->valor + ($item->valor * $data->porcentagem / 100);
        }
        return response()->json($list);
    }
    
    public function beforeUpdateData($data) {
        if(isset($data['peso']) && !empty($data['peso'])) {
            $data['peso'] = str_replace(',', '.', $data['peso']);
        }
        return $data;
    }

    public function beforeCreateData($data) {
        if(isset($data['peso']) && !empty($data['peso'])) {
            $data['peso'] = str_replace(',', '.', $data['peso']);
        }
        return $data;
    }

    public function getImages($produto_id)
    {
        try {
            $list = ProdutosImages::where('produto_id', $produto_id)->get();
            foreach($list as $image) {
                $image->path = url('/images').'/'.$image->path;
            }
            return $this->response(['data' => $list]);
        } catch(Exception $e) {
            return $this->response(['message' => $e->getMessage()], 500);
        }
    }

    public function createImages($request)
    {
        try {
            DB::beginTransaction();
            $params = $request->all();
            $filename = time().'.'.request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images'), $filename);
            ProdutosImages::create([
                'produto_id' => $params['produto_id'],
                'path' => $filename,
            ]);
            DB::commit();
            return $this->response(['message' => 'Imagem salva com sucesso.']);
        } catch(Exception $e) {
            DB::rollBack();
            return $this->response(['message' => $e->getMessage()], 500);
        }

        
    }

    public function deleteImages($id)
    {
        try {
            DB::beginTransaction();
            // $params = $request->all();
            // $filename = time().'.'.request()->image->getClientOriginalExtension();
            // request()->image->move(public_path('images'), $filename);
            ProdutosImages::where('id', $id)->delete();
            DB::commit();
            return $this->response(['message' => 'Imagem removida com sucesso.']);
        } catch(Exception $e) {
            DB::rollBack();
            return $this->response(['message' => $e->getMessage()], 500);
        }

    }

}