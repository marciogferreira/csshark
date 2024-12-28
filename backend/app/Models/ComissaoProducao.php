<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComissaoProducao extends Model
{
    protected $table = 'comissao_producaos';
    protected $fillable = [
        'id',
        'status_producao_id',
        'produto_id',
        'valor',
    ];

    public function produto() {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function status() {
        return $this->belongsTo(StatusProducao::class, 'status_producao_id');
    }
}
