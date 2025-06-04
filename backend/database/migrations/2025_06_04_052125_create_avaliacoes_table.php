<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvaliacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avaliacoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aluno_id');
            
            $table->date('data');

            $table->string('peso')->nullable();
            $table->string('altura')->nullable();
            
            $table->string('torax')->nullable();
            $table->string('abdomen')->nullable();
            $table->string('cintura')->nullable();
            $table->string('quadril')->nullable();
            $table->string('braco_direito')->nullable();
            $table->string('braco_esquerdo')->nullable();
            
            $table->string('ant_braco_direito')->nullable();
            $table->string('ant_braco_esquerdo')->nullable();

            $table->string('coxa_direito')->nullable();
            $table->string('coxa_esquerdo')->nullable();

            $table->string('panturrilha_direito')->nullable();
            $table->string('panturrilha_esquerdo')->nullable();

            $table->foreign('aluno_id')->references('id')->on('alunos');
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
        Schema::dropIfExists('avaliacoes');
    }
}
