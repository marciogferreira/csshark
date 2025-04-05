<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', '10')->unique();
            $table->float('desconto')->default(0);
            $table->integer('item_tipo_pagamento_id')->default(null)->nullable();
            $table->unsignedBigInteger('aluno_id');
            $table->unsignedBigInteger('vendedor_id');
            $table->string('tipo')->default('P');
            $table->string('status')->default('0');
            $table->text('observacao')->nullable();
            $table->foreign('aluno_id')->references('id')->on('alunos');
            $table->foreign('vendedor_id')->references('id')->on('colaboradors');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
}
