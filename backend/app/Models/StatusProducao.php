<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusProducao extends Model
{

    const STATUS_AGRUPADOS = [
        'semi_produto' => 'Semi Produto',
        'cor' => 'Cor',
    ];
    protected $table = 'status_producaos';
    protected $fillable = [
        'id',
        'nome',
        'status_producao_id',
        'agrupar_por',
        'is_final',
    ];

    public function status() {
        return $this->belongsTo(StatusProducao::class, 'status_producao_id');
    }
}
