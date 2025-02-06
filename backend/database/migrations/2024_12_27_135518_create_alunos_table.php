<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlunosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alunos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email');
            $table->string('cpf')->unique();
            $table->string('senha');
            $table->date('dataInicio')->nullable();
            $table->string('professor')->nullable();
            $table->float('peso')->nullable();
            $table->float('altura')->nullable();
            $table->string('esquerdo')->nullable();
            $table->string('direito')->nullable();
            $table->boolean('hipertensao')->default(false);
            $table->boolean('diabetes')->default(false);
            $table->boolean('fibromialgia')->default(false);
            $table->boolean('artrite')->default(false);
            $table->string('lesao')->nullable();
            $table->string('medicamentos')->nullable();
            $table->string('estadoAtivo')->nullable();
            $table->string('modalidade')->nullable();
            $table->string('frequenciaSemanal')->nullable();
            $table->string('objetivo')->nullable();
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
        Schema::dropIfExists('alunos');
    }
}
