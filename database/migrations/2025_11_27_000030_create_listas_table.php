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
        Schema::create('listas', function (Blueprint $table) {
            $table->id('idLista');
            $table->string('nombre');
            $table->string('alianza')->nullable();
            $table->string('cargoDiputado')->nullable();
            $table->string('cargoSenador')->nullable();


            $table->string('nombreProvincia');


            $table->timestamps();


            $table->foreign('nombreProvincia')
            ->references('nombre')
            ->on('provincias')
            ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('listas');
    }
};
