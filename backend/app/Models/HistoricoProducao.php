<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricoProducao extends Model
{
    protected $table = "historico_producoes";
    protected $fillable = [
        'id',
        'producao_id',
        'status',
        'status_reparacao',
        'quantidade',
        'perda',
        'observacao'
    ];
}
