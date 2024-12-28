<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LancarProduzido extends Model
{
    protected $table = 'lancar_produzidos';
    protected $fillable = [
        'id',
        'colaborador_id',
        'aux_colaborador_id',
        'status_producao_id',
        'produto_id',
        'quantidade',
        'cor_id',
        'data_lancamento',
        'observacao',
    ];

    public function produto() {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function colaborador() {
        return $this->belongsTo(Colaborador::class, 'colaborador_id');
    }

    public function colaboradorAux() {
        return $this->belongsTo(Colaborador::class, 'aux_colaborador_id');
    }

    public function statusProducao() {
        return $this->belongsTo(StatusProducao::class, 'status_producao_id');
    }

}
