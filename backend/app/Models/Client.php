<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{

    const STATUS_ACEITO = 0;
    const STATUS_REJEITADO = 1;

    protected $table = "clients";
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
        'vendedor_id',
        'tabela_id',
        'telefone',
        'observacao',
        'ativo',
        'situacao',
        'rejeitado',
        'nome_contato',
        'email_contato',
        'telefone_contato'
    ];

    public function vendedor() {
        return $this->belongsTo(Colaborador::class, 'vendedor_id');
    }

    public function tabela() {
        return $this->belongsTo(TabelaPreco::class, 'tabela_id');
    }
}


