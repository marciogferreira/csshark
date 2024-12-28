<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    protected $table = "visitas";
    protected $fillable = [
        'id',
        'cliente_id',
        'vendedor_id',
        'pedido_id',
        
        'data_visita',
        'hora_visita',

        'lat_check_in',
        'lng_check_in',
        'hora_check_in',

        'lat_check_out',
        'lng_check_out',
        'hora_check_out',

        'observacao'
    ];

    public function cliente() {
        return $this->belongsTo(Client::class, 'cliente_id');
    }

    public function vendedor() {
        return $this->belongsTo(Colaborador::class, 'vendedor_id');
    }
}
