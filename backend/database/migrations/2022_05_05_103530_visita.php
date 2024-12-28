<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Visita extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('vendedor_id');
            $table->date('data_visita');
            $table->time('hora_visita');

            $table->string('lat_check_in')->nullable();
            $table->string('lng_check_in')->nullable();
            $table->string('hora_check_in')->nullable();
            
            $table->string('lat_check_out')->nullable();
            $table->string('lng_check_out')->nullable();
            $table->string('hora_check_out')->nullable();
    
            $table->text('observacao')->nullable();
            
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
        Schema::dropIfExists('visitas');
    }
}
