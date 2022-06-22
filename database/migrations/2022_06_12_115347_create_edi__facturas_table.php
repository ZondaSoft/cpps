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
        Schema::create('edi_Facturas', function (Blueprint $table) {
            //$table->id();
            $table->increments('id_fact');
            $table->date('periodo_fac');
            $table->smallInteger('cod_os');
            $table->string('concepto', 50);
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('edi__facturas');
    }
};
