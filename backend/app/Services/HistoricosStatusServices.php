<?php
namespace App\Services;

use App\Models\HistoricosStatusPedido as Model;

class HistoricosStatusServices extends BaseServices {

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function index($params) {
        $data = $this->model->where('pedido_id', $params['pedido_id'])
            ->orderBy('id', 'desc')
            ->get();

        foreach($data as $item) {
            $item->data = $item->created_at->format('d/m/Y');
            $item->hora = $item->created_at->format('H:i:s');
            $item->status_f = $item->pedido->getStatus($item->status);
        }

        return $this->response(['data' => $data]);
    }

    public static function LancarSatus($pedido_id, $status, $observacao) {
        Model::create([
            'pedido_id' => $pedido_id,
            'status' => $status,
            'observacao' => $observacao
        ]);
    }
}