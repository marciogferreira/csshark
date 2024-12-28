<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TurmasModel extends Model
{
    protected $table = "turmas";
    protected $fillable = [
        'id',
        'nome',
        'turno',
        'valor',
        'modalidade_id',
        'colaborador_id'
    ];


}
