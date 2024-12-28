<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdutosImages extends Model
{
    protected $table = "produtos_images";
    protected $fillable = [
        'id',
        'produto_id',
        'path',
    ];
}
