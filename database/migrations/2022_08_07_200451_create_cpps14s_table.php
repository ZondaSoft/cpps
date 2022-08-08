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
        Schema::create('cpps14s', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cod_convenio');  // cpps11s
            $table->string('cod_nomenclador',10)->nullable() ;   // cpps09s
            $table->decimal('importe', 11, 2)->nullable();
            $table->timestamps();


            // FK
            //$table->foreign('cod_convenio')->references('cod_conv')->on('cpps11s');
            //$table->foreign('cod_nomenclador')->references('cod_nemotecnico')->on('cpps09s');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cpps14s');
    }
};
