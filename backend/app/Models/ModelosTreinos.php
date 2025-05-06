<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelosTreinos extends Model
{
    protected $table = "modelos_treinos";
    protected $fillable = [
        'id',
        'nome',
        'data'
    ];
}
