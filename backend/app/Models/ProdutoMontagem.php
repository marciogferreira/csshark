<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdutoMontagem extends Model
{
    protected $table = "produto_montagems";
    protected $fillable = [
        'id',
        'produto_id',
        'produto_montagem_id',
        'quantidade',
    ];

    public function produto() {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function produtoMontagem() {
        return $this->belongsTo(Produto::class, 'produto_montagem_id');
    }
}
