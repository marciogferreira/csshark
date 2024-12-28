<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItensTiposPagamentos extends Model
{
    protected $table = "itens_tipos_pagamentos";
    protected $fillable = [
        'id',
        'forma_pagamento_id',
        'descricao',
        'dias',
        'preco_desconto',
        'desconto'
    ];

    public function formaPagamento() {
       return $this->belongsTo(FormaPagamento::class, 'forma_pagamento_id');
    }
}
