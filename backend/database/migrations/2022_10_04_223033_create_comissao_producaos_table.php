<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComissaoProducaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comissao_producaos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('status_producao_id');
            $table->unsignedBigInteger('produto_id');
            $table->float('valor');
            
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
        Schema::dropIfExists('comissao_producaos');
    }
}
