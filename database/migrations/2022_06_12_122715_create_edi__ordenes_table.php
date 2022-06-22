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
        Schema::create('edi_Ordenes', function (Blueprint $table) {
            //$table->id();
            $table->integer('id_fact', 10);
            $table->integer('nro_renglon')->nullable();
            $table->integer('cod_prof')->nullable();
            $table->string('ordennro', 20)->nullable();
            $table->integer('id_nomen')->nullable();
            $table->string('nom_afiliado', 50)->nullable();
            $table->double('importe')->nullable();
            $table->smallInteger('cantidad')->nullable();
            $table->smallInteger('estado_orden')->nullable();
            $table->integer('dni_afiliado')->nullable();
            //$table->timestamps();

            // FK
            //$table->foreign('id_fact')->references('id_fact')->on('edi_Facturas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('edi__ordenes');
    }
};
