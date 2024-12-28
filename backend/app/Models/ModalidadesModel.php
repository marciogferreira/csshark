<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModalidadesModel extends Model
{
    protected $table = "modalidades";
    protected $fillable = [
        'id',
        'nome'
    ];


}
