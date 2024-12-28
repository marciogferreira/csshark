<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlunosTurmasModel extends Model
{
    protected $table = "alunos_turmas";
    protected $fillable = [
        'id',
        'observacao',
        'aluno_id',
        'turma_id',
        'valor',
        'desconto',
        'data_inicio',
    ];

    public function aluno() {
        return $this->belongsTo('App\Models\AlunosModel', 'aluno_id');
    }

    public function turma() {
        return $this->belongsTo('App\Models\TurmasModel', 'turma_id');
    }


}
