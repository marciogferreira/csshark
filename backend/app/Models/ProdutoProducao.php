<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdutoProducao extends Model
{
    protected $table = "produto_producao";
    protected $fillable = [
        'id',
        'produto_id',
        'status_producao_id',
    ];

    public function statusProducao(){
        return $this->belongsTo(StatusProducao::class, 'status_producao_id');
    }
}
