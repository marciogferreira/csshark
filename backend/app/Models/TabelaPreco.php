<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TabelaPreco extends Model
{
    protected $table = 'tabela_precos';
    protected $fillable = [
        'id',
        'name',
        'porcentagem'
    ];
}
