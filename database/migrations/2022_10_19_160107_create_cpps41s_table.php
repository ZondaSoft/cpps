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
        Schema::create('cpps41s', function (Blueprint $table) {
            $table->id();
            $table->Integer('cod_prof')->nullable();
            $table->Integer('mat_prov_cole')->nullable();
            $table->integer('num_liq')->nullable();
            $table->date('fecha_liq')->nullable();
            $table->double('total')->nullable();
            $table->double('msr')->nullable();
            $table->double('egreso')->nullable();
            $table->integer('ultima_num_liq')->nullable();
            $table->date('fecha_ultima_liq')->nullable();
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
        Schema::dropIfExists('cpps41s');
    }
};
