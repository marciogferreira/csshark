<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPedido extends Model
{
    protected $table = "itens_pedidos";
    protected $fillable = [
        'id',
        'preco',
        'quantidade',
        'desconto',
        'pedido_id',
        'produto_id'
    ];

    public function pedido() {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function produto() {
        return $this->belongsTo(Produto::class, 'produto_id');
    }
    
}
