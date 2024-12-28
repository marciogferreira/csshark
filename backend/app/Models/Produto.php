<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{

    const TIPO_PRODUTO = 'produto';
    const TIPO_SEMI_PRODUTO = 'semi_produto';
    const TIPO_MATERIA = 'materia';

    protected $table = "produtos";
    protected $fillable = [
        'id',
        'titulo',
        'descricao',
        'valor',
        'custo',
        'quantidade',
        'tipo_unidade',
        'fornecedor_id',
        'cor_id',
        'tipo',
        'codigo',
        'codigo_geral',
        'peso',
        'desconto',
        'ncm'
    ];
    
    public function itensMontagem() {
        return $this->hasMany(ProdutoMontagem::class, 'produto_id');
    }

    public function cores() {
        return $this->belongsTo(Cor::class, 'cor_id');
    }

}
