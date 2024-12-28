<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producao extends Model
{
    const STATUS_ALUMINIO_MOLDACAO = 'MOLDACAO';
    const STATUS_ALUMINIO_POLIMENTO = 'POLIMENTO';
    const STATUS_ALUMINIO_LIXAMENTO = 'LIXAMENTO';
    const STATUS_ALUMINIO_MONTAGEM = 'MONTAGEM';
    const STATUS_ALUMINIO_PINTURA = 'PINTURA';
    
    const STATUS_GRELHA_MOLDAR = 'MOLDAR';
    const STATUS_GRELHA_SOLDAR = 'SOLDAR';
    const STATUS_GRELHA_CROMAR = 'CROMAR';
    const STATUS_GRELHA_MONTAR = 'MONTAR';

    const TIPO_PECA_ALUMINIO = 'ALUMINIO';
    const TIPO_PECA_GRELHA = 'GRELHA';

    const STATUS_CRIACAO = 'CRIACAO';
    const STATUS_REPACAO_CRIACAO = 'REPARACAO_DE_CRIACAO';
    const STATUS_REPACAO_CLIENTE = 'REPARACAO_ESTOQUE';
    
    protected $table = "producoes";
    protected $fillable = [
        'id',
        'colaborador_id',
        'colaborador_aux_id',
        'produto_id',
        'quantidade',
        'status',
        'status_reparacao',
        'observacao',
        'tipo_peca'
    ];

    public function produto() {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function colaborador() {
        return $this->belongsTo(Colaborador::class, 'colaborador_id');
    }

    public function colaboradorAux() {
        return $this->belongsTo(Colaborador::class, 'colaborador_aux_id');
    }

}
