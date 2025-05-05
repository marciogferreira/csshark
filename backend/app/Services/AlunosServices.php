<?php
namespace App\Services;

use App\Models\AlunosModel as Model;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class AlunosServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        $this->columnSearch = 'nome';
        $this->indexOptions = [
            'value' => 'id',
            'label' => 'nome'
        ];
        $this->model = $model;
    }


    public function index($request) {
        $params = $request->all();

        $data = $this->model->when($params, function($query, $params) {
            if(isset($params['search'])) {
                $query->whereRaw("
                    ({$this->columnSearch} like '%{$params['search']}%' 
                    OR 
                    cpf like '%{$params['search']}%'
                    )
                ");
            }
            return $query;
        })
        ->when($this->orderBy, function($query, $orderBy) {
            if($orderBy === 'desc') {
                return $query->orderBy('id', 'desc');
            }
        })
        ->paginate(10);
        return $this->response($data);
    }

    function beforeCreateData($data) {
        
        $aluno = Model::where('cpf', $data['cpf'])->first();
        if($aluno) {
            throw new Exception('Já existe um(a) aluno(a) cadastrado(a) com esse CPF. Por favor, dirija-se a recepção do Box.');
        }

        if(isset($data['cpf']) && !empty($data['cpf'])) {
            $data['senha'] = md5($data['cpf']);
        }
        return $data;
    }

    
    public function updateStatus($request, $id) {
        try {
            DB::beginTransaction();
            if($this->validator) {
                $validator = $this->validator->validation($request);
                if($validator->fails()){
                    return $this->response(['validation' => $validator->errors()], 422);
                }
            }
            $params = $request->all();
            $this->params = $params;
            $data = $this->model->find($id);
            if(!isset($params['id'])) {
                $params['id'] = $id;
            }
            $params['model'] = $data;
            $params['data_ultima_ativacao'] = Carbon::now('Y-m-d');
            $params = $this->beforeUpdateData($params);
            // $params = $this->update($params);
            $data->update($params);
            $this->afterUpdateData($data);
            DB::commit();
            return $this->response(['message' => 'Registro Atualizado com Sucesso']);
        } catch(Exception $e) {
            DB::rollBack();
            return $this->response(['message' => $e->getMessage(). ' Código: '.$e->getLine()], 500);
        }
    }



}