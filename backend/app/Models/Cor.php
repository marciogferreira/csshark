<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cor extends Model
{
    protected $table = 'cors';
    protected $fillable = [
        'id',
        'name'
    ];
    
    public function produtos() {
        return $this->hasMany(Produto::class, 'produto_id');
    }
}
