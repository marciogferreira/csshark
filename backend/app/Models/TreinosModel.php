<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreinosModel extends Model
{
    protected $table = "treinos";
    protected $fillable = [
        'id',
        'aluno_id',
        'data'
    ];

    public function aluno() {
        return $this->belongsTo('App\Models\AlunosModel', 'aluno_id');
    }

}
