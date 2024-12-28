<?php
namespace App\Services;

use App\Models\TipoDespesa as Model;

class TipoDespesaServices extends BaseServices {
    
    public function __construct(Model $model)
    {
        
        $this->model = $model;
    }

    public function index($request) {
        $params = $request->all();
        if(isset($params['search'])) {
            $data = $this->model->where('name', 'like', "%{$params['search']}%")->paginate(10);
        } else {
            $data = $this->model->paginate(10);
        }
        foreach($data as $item) {
            $item->vendedor;
        }
        return $this->response($data);
    }


}