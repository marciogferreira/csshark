<?php
namespace App\Services\Relatorios;

use App\Models\DependenciasModel;
use App\Models\HistoricosStatusPedido;
use App\Models\Pedido as Model;
use App\Models\Produto;
use App\Services\NegociosServices;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PedidoRelatorios extends RelatoriosServices {
    
    public function config($params) {
        $this->model = new Model();
        $data = $this->model->find($params['pedido_id']);
        $data->vendedor;
        $data->cliente;
        $data->itens = NegociosServices::getItensByOrderIdOrderProduct($data->id);

        $historicos = HistoricosStatusPedido::where('pedido_id', $data->id)
            ->where('observacao', '<>', "")
            ->orderBy('id', 'desc')->get();
        
        foreach($historicos as $historico) {
            $date = Carbon::parse($historico->created_at);
            $historico->data = $date->format('d/m/Y');
            $historico->hora = $date->format('H:i:s');
            $historico->status = $historico->getStatus($historico->status);
        }
        $data->historicos = $historicos;

        $total = 0;
        $peso_total = 0;
        
        foreach($data->itens as $item) {
            $item->produto = Produto::find($item->produto_id);
            $item->exibir = true;
            $isDependencia = DependenciasModel::where('dependencia_id', $item->produto_id)->first();
            if($isDependencia) {
                $item->exibir = false;
            }
            $total = $total + ($item->quantidade * $item->preco);
            $peso_total = $peso_total + ($item->quantidade * (float) $item->produto->peso);
        }
        $data->total = $total;
        $data->peso_total = $peso_total;
        $data->desconto_maior = '';

        $this->name = 'pedido';
        $this->view = 'relatorios.pedido';
        $this->data = $data;
    }

}