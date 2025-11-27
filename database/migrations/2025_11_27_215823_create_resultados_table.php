<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resultados', function (Blueprint $table) {
            $table->integer('idResultado')->primary();
            $table->integer('votos');
            $table->decimal('porcentaje',5,2);
            $table->integer('idLista');
            $table->integer('idTelegrama');


            $table->timestamps();


            $table->foreign('idLista')->references('idLista')->on('Lista');
            $table->foreign('idTelegrama')->references('idTelegrama')->on('Telegrama');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resultados');
    }
};
