<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdemProducao extends Model
{
    protected $table = 'ordem_producaos';
    protected $fillable = [
        'id',
        'observacao',
    ];
}
