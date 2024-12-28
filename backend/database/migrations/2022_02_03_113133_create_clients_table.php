<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('razao_social')->nullable();
            $table->string('nome_fantasia')->nullable();
            $table->string('cnpj')->nullable();
            $table->string('cpf')->nullable();
            $table->string('logradouro')->nullable();
            $table->string('numero')->nullable();
            $table->string('complemento')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cidade')->nullable();
            $table->string('uf')->nullable();
            $table->string('cep')->nullable();
            $table->string('fone')->nullable();
            $table->string('celular')->nullable();
            $table->string('email')->nullable();

            $table->string('telefone')->nullable();
            $table->text('observacao')->nullable();
            $table->boolean('situacao')->default(true);
            $table->string('nome_contato')->nullable();
            $table->string('email_contato')->nullable();
            $table->string('telefone_contato')->nullable();

            $table->unsignedBigInteger('vendedor_id');
            $table->unsignedBigInteger('tabela_id');
            $table->timestamps();

            $table->foreign('vendedor_id')->references('id')->on('colaboradors');
            $table->foreign('tabela_id')->references('id')->on('tabela_precos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
