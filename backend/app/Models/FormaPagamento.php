<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormaPagamento extends Model
{
    protected $table = "forma_pagamentos";
    protected $fillable = [
        'id',
        'name',
        'tipo_pagamento',
        'desconto'
    ];

    public function itensTiposPagamentos() {
        return $this->hasMany(ItensTiposPagamentos::class, 'forma_pagamento_id');
    }

    public function getTipoPagamento() {
        $list = [
            '1' => 'Dinheiro',
            '2' => 'Transferência Ted/Doc',
            '3' => 'Pix',
            '4' => 'Cheque',
            '5' => 'Boleto',
            '6' => 'Cartão',
            'Selecione' =>  'Não Selecionado',
        ];
        
        if($this->tipo_pagamento) {
            return $list[$this->tipo_pagamento];
        }
        return '';
        
    }
}
