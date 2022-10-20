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
        Schema::create('cpps42s', function (Blueprint $table) {
            $table->id();
            $table->integer('num_liq')->nullable();
            $table->integer('num_renglon')->nullable();
            $table->Integer('cod_prof')->nullable();
            $table->Integer('mat_prov_cole')->nullable();
            $table->string('operacion', 1)->nullable();
            $table->integer('cod_operacion')->nullable();
            $table->string('desc_operacion', 50)->nullable();
            $table->double('importe_op')->nullable();
            $table->date('periodo')->nullable();
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
        Schema::dropIfExists('cpps42s');
    }
};
