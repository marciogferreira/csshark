<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DependenciasModel extends Model
{
    protected $table = "dependencias";
    protected $fillable = [
        'id',
        'produto_id',
        'dependencia_id',
        'quantidade',
    ];

    public function produto() {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function produtoDependencia() {
        return $this->belongsTo(Produto::class, 'dependencia_id');
    }
}
