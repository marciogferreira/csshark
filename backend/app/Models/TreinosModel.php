<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreinosModel extends Model
{
    protected $table = "treinos";
    protected $fillable = [
        'id',
        'nome',
        'data'
    ];


}
