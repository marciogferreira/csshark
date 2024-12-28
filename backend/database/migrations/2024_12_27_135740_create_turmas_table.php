<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTurmasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turmas', function (Blueprint $table) {
            $table->id();

            $table->string('nome');
            $table->string('turno');
            $table->float('valor')->default(0);
            $table->unsignedBigInteger('modalidade_id');
            $table->unsignedBigInteger('colaborador_id');

            $table->foreign('modalidade_id')->references('id')->on('modalidades');
            $table->foreign('colaborador_id')->references('id')->on('colaboradors');
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
        Schema::dropIfExists('turmas');
    }
}
