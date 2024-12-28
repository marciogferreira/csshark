<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlunosTreinosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alunos_treinos', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('aluno_id');
            $table->unsignedBigInteger('treino_id');
            $table->string('observacao');

            $table->foreign('aluno_id')->references('id')->on('alunos');
            $table->foreign('treino_id')->references('id')->on('treinos');
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
        Schema::dropIfExists('alunos_treinos');
    }
}
