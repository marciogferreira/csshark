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

    public function treino() {
        return $this->belongsTo(TreinosModel::class, 'treino_id');
    }

    public function aluno() {
        return $this->belongsTo(AlunosModel::class, 'aluno_id');
    }
}
