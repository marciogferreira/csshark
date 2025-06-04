<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avaliacoes extends Model
{
    protected $table = 'avaliacoes';

    protected $fillable = [
        'aluno_id',
        'data',
        'peso',
        'altura',
        'torax',
        'abdomen',
        'cintura',
        'quadril',
        'braco_direito',
        'braco_esquerdo',
        'ant_braco_direito',
        'ant_braco_esquerdo',
        'coxa_direito',
        'coxa_esquerdo',
        'panturrilha_direito',
        'panturrilha_esquerdo',
    ];

}
