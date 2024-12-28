<?php

namespace App\Models;

use App\User;
use App\Models\Cargos;
use Illuminate\Database\Eloquent\Model;

class Colaborador extends Model
{
    protected $table = "colaboradors";
    protected $fillable = [
        'id',
        'name',
        'email',
        'telefone',
        'whatsapp',
        'endereco',
        'cargo_id',
        'comissao',
    ];

    public function user() {
        return $this->hasOne(User::class, 'colaborador_id');
    }
    public function cargo() {
        return $this->belongsTo(Cargos::class, 'cargo_id');
    }

}
