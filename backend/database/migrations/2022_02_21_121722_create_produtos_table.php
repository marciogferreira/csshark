<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo')->nullable();
            $table->text('descricao')->nullable();
            $table->float('valor')->nullable();
            $table->float('custo')->nullable();
            $table->integer('quantidade')->nullable();
            $table->string('tipo_unidade')->nullable();

            $table->string('tipo')->nullable();
            $table->string('codigo')->nullable();
            $table->string('codigo_geral')->nullable();
            $table->float('peso')->nullable();

            $table->unsignedBigInteger('fornecedor_id')->nullable();
            $table->foreign('fornecedor_id')->references('id')->on('fornecedors');
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
        Schema::dropIfExists('produtos');
    }
}
