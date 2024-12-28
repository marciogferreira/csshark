<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LancamentosComissoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lancamentos_comissoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pedido_id');
            $table->unsignedBigInteger('autorizado_por_id');

            $table->float('desconto');
            $table->float('comissao');
            $table->string('obervacao')->nullable();
            $table->string('status')->nullable()->default('0');

            $table->foreign('pedido_id')->references('id')->on('pedidos');
            $table->foreign('autorizado_por_id')->references('id')->on('users');
            
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
        //
    }
}

