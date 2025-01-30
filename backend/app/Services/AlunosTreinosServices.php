<?php
namespace App\Services;

use App\Models\AlunosModel;
use App\Models\AlunosTreinosModel as Model;
use App\Models\TreinosModel;

class AlunosTreinosServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->columnSearch = 'nome';
        $this->model = $model;
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
            $item->aluno;
            $item->treino;
        }

        return $this->response($data);
    }

    public function show($id) {
        $data = $this->model->find($id);
        $treino = TreinosModel::find($data->treino_id);
        $data->data = $treino->data;
        return response()->json(['data' => $data]);
    }


    public function beforeCreateData($params) {
        $params['data'] = json_encode($params['data']);
        $treino = TreinosModel::create([
            'nome' => ' Treino',
            'data' => $params['data']
        ]);
        $params['treino_id'] = $treino->id;

        return $params;
    }

    public function beforeUpdateData($params) {
        $params['data'] = json_encode($params['data']);

        TreinosModel::find($params['treino_id'])->update([
            'data' => $params['data']
        ]);

        return $params;
    }

    public function fichaByAluno($cpf) {
        $aluno = AlunosModel::where('cpf', $cpf)->first();
        $treino = TreinosModel::where('aluno_id', $aluno->id)->first();
        return response()->json($treino);
    }
    
}