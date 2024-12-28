<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstoqueProducao extends Model
{
    protected $table = 'estoque_producaos';
    protected $fillable = [
        'id',
        'status_producao_id',
        'produto_id',
        'quantidade',
        'cor_id'
    ];

    public function produto() {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function status() {
        return $this->belongsTo(StatusProducao::class, 'status_producao_id');
    }
}
