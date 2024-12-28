<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutoMontagemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return voidprodutos
     */
    public function up()
    {
        Schema::create('produto_montagems', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produto_id');
            $table->unsignedBigInteger('produto_montagem_id');
            $table->string('quantidade')->nullable();

            $table->foreign('produto_id')->references('id')->on('produtos');
            $table->foreign('produto_montagem_id')->references('id')->on('produtos');
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
        Schema::dropIfExists('produto_montagems');
    }
}
