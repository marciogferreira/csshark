<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    protected $table = "fornecedors";
    protected $fillable = [
        'id',
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'cpf',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        'cep',
        'fone',
        'celular',
        'email',
        'telefone',
        'observacao',
        'situacao',
        'nome_contato',
        'email_contato',
        'telefone_contato'
    ];
}
