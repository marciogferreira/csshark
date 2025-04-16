<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Frequencias extends Model
{
    protected $table = 'frequencias';
    protected $fillable = [
        'id',
        'aluno_id',
        'data',
        'hora',
        'tipo_treino'
    ];

    public function aluno() {
        return $this->belongsTo('App\Models\AlunosModel', 'aluno_id');
    }
}
