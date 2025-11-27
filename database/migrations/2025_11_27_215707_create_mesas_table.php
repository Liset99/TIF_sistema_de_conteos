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
        Schema::create('mesas', function (Blueprint $table) {
            $table->integer('idMesa')->primary();
            $table->integer('electores');
            $table->string('establecimiento');
            $table->string('circuito');
            $table->string('nombreProvincia');


            $table->timestamps();


            $table->foreign('nombreProvincia')->references('nombre')->on('provincias');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mesas');
    }
};
