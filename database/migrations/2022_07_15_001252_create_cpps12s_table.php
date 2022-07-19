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
        Schema::create('cpps12s', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('cod_conv')->unsigned();
            $table->Integer('cod_os')->unsigned();
            $table->Integer('estado_convos')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_baja')->nullable();
            $table->Integer('cod_categoria')->nullable();
            $table->timestamps();

            // FK
            $table->foreign('cod_conv')->references('cod_conv')->on('cpps11s');
            //$table->foreign('cod_os')->references('cod_os')->on('cpps07s');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cpps12s');
    }
};
