<?php
namespace App\Services;

use App\User as Model;
use Illuminate\Support\Facades\Hash;

class UsersServices extends BaseServices {
   
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->indexOptions = [
            'value' => 'id',
            'label' => 'name',
        ];
        $this->columnSearch = 'name';
    }

    public function index($request) {
        $params = $request->all();
        if(isset($params['search'])) {
            $data = $this->model->where($this->columnSearch, 'like', "%{$params['search']}%")->paginate(10);
        } else {
            $data = $this->model->paginate(10);
        }

        foreach($data as $item) {
            if($item->ativo) {
                $item->ativo_f = "Ativo";
            } else {
                $item->ativo_f = "Inativo";
            }
        }
        return $this->response($data);
    }

    public function beforeCreateData($data)
    {
        $data['password'] = Hash::make($data['password']);
        return $data;
    }

    public function beforeUpdateData($data)
    {
        if(isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
            return $data;    
        }
        return $data;
    }

}