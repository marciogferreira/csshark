<?php
namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;

class BaseServices {

    protected $model;   
    protected $validator = null;
    protected $params = [];
    protected $user = null;
    protected $columnSearch = 'title';
    protected $orderBy = 'asc';
    protected $indexOptions = [
        'value' => 'id',
        'label' => 'name',
    ];

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
        return $this->response($data);
    }

    public function show($id) {
        $data = $this->model->find($id);
        return response()->json(['data' => $data]);
    }

    // public function store($params) {
    //     return $params;
    // }

    public function store($request) {
        try{
            DB::beginTransaction();
            if($this->validator) {
                $validator = $this->validator->validation($request);
                if($validator->fails()){
                    return $this->response(['validation' => $validator->errors()], 422);
                }
            }
            $this->user = $request->user();
            $params = $this->beforeCreateData($request->all());
            $this->params = $params;
            $data = $this->model->create($params);
            $this->afterCreateData($data);
            $data = $this->sanitizeDataCreate($data);
            DB::commit();
            return $this->response(['message' => 'Registro Atualizado com Sucesso', 'data' => $data]);
            
        } catch(Exception $e) {
            DB::rollBack();
            return $this->response(['message' => $e->getMessage()], 500);
        }
    }

    // public function update($params) {
    //     return $params;
    // }

    public function update($request, $id) {
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

    // public function destroy($data) {
    // }

    public function destroy($id) {
        try {
            DB::beginTransaction();
            $data = $this->model->find($id);
            $this->beforeDataDelete($data);
            // $this->destroy($data);
            $data->delete();
            DB::commit();
            return $this->response(['message' => 'Registro Deletado com Sucesso']);
        } catch(Exception $e) {
            DB::rollback();
            return $this->response(['message' => $e->getMessage()], 500);
        }
    }

    public function response($data, $status = 200) {
        return response()->json($data, $status);
    }

    public function afterCreateData($data){}
    public function beforeCreateData($data)
    {
        return $data;
    }
    public function afterUpdateData($data){}
    public function beforeUpdateData($data)
    {
        return $data;
    }
    public function beforeDataDelete($data) {}
    public function sanitizeDataCreate($data) {return $data;}
    

    public function beforeCreateSanitizeData($model) {
        return $model;
    }
    public function options($params) {
        $list = $this->model->all();
        $res = [];
        $value = $this->indexOptions['value'];
        $label = $this->indexOptions['label'];
        
        foreach($list as $item) {
            $res[] = [
                'value' => $item->$value,
                'label' => $item->$label,
            ];
        }
        
        return response(['data' => $res]);
    }
    
    public function listOptions() {
        $list = $this->repository->findAll();
        $result = [];
        foreach($list as $item) {
            $result[$item->name] = $item->id;
        }
        return $result;
    }
    public function isIssetEmpty($params, $name, $value = '') {
        if(isset($params[$name])) {
            if(!empty($params[$name])) {
                return $params[$name];
            }
        }
        return $value;
    }
    
}