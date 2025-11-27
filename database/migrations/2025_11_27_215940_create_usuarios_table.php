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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->integer('idUsuario')->primary();
            $table->string('nombreDeUsuario');
            $table->string('contrasenia');
            $table->string('rol');
            $table->string('dni');


            $table->timestamps();


            $table->foreign('dni')->references('dni')->on('Personas');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
};
