<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlunosModel extends Model
{
    protected $table = "alunos";
    protected $fillable = [
        'id',
        'nome',
        'email',
        'cpf',
        'senha',
        'dataInicio',
        'professor',
        'peso',
        'altura',
        'esquerdo',
        'direito',
        'hipertensao',
        'diabetes',
        'fibromialgia',
        'artrite',
        'lesao',
        'medicamentos',
        'estadoAtivo',
        'modalidade',
        'frequenciaSemanal',
        'objetivo'
    ];


}
