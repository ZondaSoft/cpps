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
        Schema::create('cpps11s', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('cod_conv');
            $table->string('desc_conv',50)->nullable();
            $table->string('observacion_conv',200)->nullable();
            $table->boolean('estado_conv')->default(0);
            $table->date('fecha_alta')->nullable();
            $table->date('fecha_baja')->nullable();
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
        Schema::dropIfExists('cpps11s');
    }
};
