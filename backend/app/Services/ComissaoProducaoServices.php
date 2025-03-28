<?php
namespace App\Services;

use App\Models\ComissaoProducao as Model;

class ComissaoProducaoServices extends BaseServices {
    
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
        }

        return $this->response($data);
    }
    

}