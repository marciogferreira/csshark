<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoricoProducaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historico_producoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producao_id');
            $table->string('status');
            $table->string('status_reparacao');
            $table->integer('quantidade');
            $table->integer('perda')->nullable();
            $table->string('observacao')->nullable();

            $table->foreign('producao_id')->references('id')->on('producoes');
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
        Schema::dropIfExists('historico_producoes');
    }
}
