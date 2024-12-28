<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ItensTiposPagamentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itens_tipos_pagamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('forma_pagamento_id');
            $table->string('descricao');
            $table->integer('dias')->default(0);
            $table->float('preco_desconto')->default(0);
            $table->float('desconto')->default(0);

            $table->foreign('forma_pagamento_id')->references('id')->on('forma_pagamentos');
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
        Schema::dropIfExists('itens_tipos_pagamentos');
    }
}
