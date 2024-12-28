<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricosStatusPedido extends Model
{
    protected $table = "historicos_status_pedidos";
    protected $fillable = [
        'id',
        'pedido_id',
        'status',
        'observacao'
    ];

    public function pedido() {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function getStatus($status = null) {
        
        $list = [
            'open' => 'Em Aberto',
            'waiting' => 'Aguardando',
            'pending' => 'Pendente',
            'approved' => 'Aprovado',
            'production' => 'Em Produção',
            'travel' => 'Em Viagem',
            'finished' => 'Finalizado',
            'cancel' => 'Cancelado',
        ];
        if($status) {
            return $list[$status];
        }
        return $list[$this->status];
    }
}
