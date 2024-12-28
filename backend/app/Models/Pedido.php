<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ItensTiposPagamentos;

class Pedido extends Model
{

    const STATUS_ABERTO = 'open';
    const STATUS_AGUARDANDO = 'waiting';
    const STATUS_PENDENTE = 'pending';
    const STATUS_APROVADO = 'approved';
    const STATUS_PRODUCAO = 'production';
    const STATUS_VIAGEM = 'travel';
    const STATUS_FINALIZADO = 'finished';
    const STATUS_CANCELADO = 'cancel';


    const STATUS_TIPO_PEDIDO = 'P';
    const STATUS_TIPO_ORCAMENTO = 'O';
    const STATUS_TIPO_REPARO = 'R';
    
    protected $table = "pedidos";
    protected $fillable = [
        'id',
        'codigo',
        'desconto',
        'tabela_id',
        'cliente_id',
        'vendedor_id',
        'forma_pagamento_id',
        'item_tipo_pagamento_id',
        'tipo',
        'frete',
        'status',
        'observacao',
        'ordem_producao_id',
        'ordem_producao_correto_id',
    ];

    public function tabela() {
        return $this->belongsTo(TabelaPreco::class, 'tabela_id');
    }

    public function cliente() {
        return $this->belongsTo(Client::class, 'cliente_id');
    }

    public function vendedor() {
        return $this->belongsTo(Colaborador::class, 'vendedor_id');
    }

    public function itens() {
        return $this->hasMany(ItemPedido::class, 'pedido_id');
    }

    public function forma_pagamento() {
        return $this->belongsTo(FormaPagamento::class, 'forma_pagamento_id');
    }

    public function tipo_pagamento() {
        return $this->belongsTo(ItensTiposPagamentos::class, 'item_tipo_pagamento_id');
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

    public function listStatusOptions($data){

        $lista = [];
        if($data->status != Pedido::STATUS_APROVADO && $data->forma_pagamento_id) {
            $lista[] = ['value' => Pedido::STATUS_APROVADO, 'label' => 'Aprovar'];
        }
        // if($data->status == Pedido::STATUS_AGUARDANDO && $data->forma_pagamento_id) {
        //     $lista[] = ['value' => Pedido::STATUS_APROVADO, 'label' => 'Aprovar'];
        // }

        // if($data->status == Pedido::STATUS_PENDENTE && !$data->forma_pagamento_id) {
        //     $lista[] = ['value' => Pedido::STATUS_APROVADO, 'label' => 'Aprovar'];   
        // }

        // if($data->status == Pedido::STATUS_PENDENTE && $data->forma_pagamento_id) {
        //     $lista[] = ['value' => Pedido::STATUS_APROVADO, 'label' => 'Aprovar'];   
        // }
        

        if($data->status != Pedido::STATUS_FINALIZADO) {
            $lista[] = ['value' => Pedido::STATUS_PENDENTE, 'label' => 'Lançar Pendência'];   
        }

        if($data->status == Pedido::STATUS_APROVADO && $data->forma_pagamento_id) {
            $lista[] = ['value' => Pedido::STATUS_PRODUCAO, 'label' => 'Em Produção'];   
        }

        if($data->status == Pedido::STATUS_PRODUCAO && $data->forma_pagamento_id) {
            $lista[] = ['value' => Pedido::STATUS_VIAGEM, 'label' => 'Em Viagem'];
        }

        if($data->status == Pedido::STATUS_FINALIZADO && $data->status != Pedido::STATUS_CANCELADO) {
            $lista[] = ['value' => Pedido::STATUS_FINALIZADO, 'label' => 'Entregar'];   
        }

        if($data->status == Pedido::STATUS_VIAGEM) {
            $lista[] = ['value' => Pedido::STATUS_FINALIZADO, 'label' => 'Entregar'];   
        }
       
        if($data->status != Pedido::STATUS_FINALIZADO) {
            $lista[] = ['value' => Pedido::STATUS_CANCELADO, 'label' => 'Cancelar'];   
        }
        
        return $lista;
    }

}
