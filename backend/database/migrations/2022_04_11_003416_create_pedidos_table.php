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

            $table->unsignedBigInteger('tabela_id');
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('vendedor_id');

            $table->string('status')->default('0');

            $table->foreign('tabela_id')->references('id')->on('tabela_precos');
            $table->foreign('cliente_id')->references('id')->on('clients');
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
