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
        Schema::create('cpps40s', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_comprob',3)->nullable();
            $table->Integer('pventa');
            $table->Integer('numero');
            $table->string('periodo',7)->nullable();
            $table->Integer('cod_os');
            $table->string('concepto', 50)->nullable();
            $table->date('fecha')->nullable();
            $table->tinyInteger('estado')->default(0);
            $table->double('importe')->nullable();
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
        Schema::dropIfExists('cpps40s');
    }
};
