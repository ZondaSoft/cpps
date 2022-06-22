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
        Schema::create('facturas', function (Blueprint $table) {
            //$table->id();
            $table->increments('id_fact');
            $table->smallInteger('num_suc');
            $table->integer('num_fact');
            $table->date('fch_fac');
            $table->date('periodo_fac');
            $table->smallInteger('cod_os');
            $table->string('concepto', 50);
            $table->string('observacion', 100)->nullable();
            $table->integer('cant_orden')->nullable();
            $table->decimal('importe_fac', 11, 2)->nullable();
            $table->decimal('porcent_cancelado', 11, 2)->nullable();
            $table->string('estado_fac', 1)->nullable();
            $table->boolean('internet')->nullable();
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
        Schema::dropIfExists('facturas');
    }
};
