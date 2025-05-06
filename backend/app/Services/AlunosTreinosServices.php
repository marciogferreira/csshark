<?php
namespace App\Services;

use App\Models\AlunosModel;
use App\Models\AlunosTreinosModel as Model;
use App\Models\AlunosTreinosModel;
use App\Models\TreinosModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class AlunosTreinosServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->columnSearch = 'nome';
        $this->model = $model;
    }

    public function index($request) {
        $params = $request->all();
        $search = $params['search'];
        $data = AlunosTreinosModel::when($params, function($query, $params) {
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
        if($aluno) {
            
            if(!$aluno->data_ultima_ativacao) {
                $aluno->data_ultima_ativacao = $aluno->created_at->format('Y-m-d');
                $aluno->save();
            }

            if($aluno->data_ultima_ativacao) {
                $dataHoje = Carbon::now()->format('Y-m-d');
                $dataUltimoStatus = Carbon::parse($aluno->data_ultima_ativacao)->addMonth(1)->format('Y-m-d');
                if($dataHoje > $dataUltimoStatus) {
                    $aluno->status = false;
                    $aluno->save();
                }
            }

            $treino = TreinosModel::where('aluno_id', $aluno->id)->first();
            if(!$aluno->status) {
                $treino = null;
            }
            return response()->json($treino);
        }
        return response()->json([]);
        
    }

    public function fichaByAlunoId($id) {
        $treino = TreinosModel::find($id);
        $treino->aluno;
        return response()->json($treino);
        return response()->json([]);
        
    }
    
    
}