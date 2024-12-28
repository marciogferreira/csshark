<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLancarProduzidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lancar_produzidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('colaborador_id');
            $table->unsignedBigInteger('aux_colaborador_id')->nullable();
            $table->unsignedBigInteger('status_producao_id');
            $table->unsignedBigInteger('produto_id');
            $table->integer('quantidade');
            $table->date('data_lancamento');
            $table->text('observacao')->nullable();

            $table->foreign('colaborador_id')->references('id')->on('colaboradors');
            $table->foreign('aux_colaborador_id')->references('id')->on('colaboradors');
            $table->foreign('status_producao_id')->references('id')->on('status_producaos');
            $table->foreign('produto_id')->references('id')->on('produtos');
            
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
        Schema::dropIfExists('lancar_produzidos');
    }
}
