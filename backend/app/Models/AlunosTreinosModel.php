<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlunosTreinosModel extends Model
{
    protected $table = "alunos_treinos";
    protected $fillable = [
        'id',
        'aluno_id',
        'treino_id',
        'observacao',
    ];
}
