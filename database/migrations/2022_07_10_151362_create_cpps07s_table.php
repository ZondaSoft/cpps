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
        Schema::create('cpps07s', function (Blueprint $table) {
            $table->id();
            $table->Integer('cod_os');
            $table->string('desc_os',50)->nullable();
            $table->string('estado_os')->default(0);
            $table->date('fcha_alta')->nullable();
            $table->string('contacto',50)->nullable();
            $table->string('direccion_os',50)->nullable();
            $table->string('cp',20)->nullable();
            $table->string('localidad',50)->nullable();
            $table->string('provincia',30)->nullable();
            $table->string('telefono1',30)->nullable();
            $table->string('telefono2',30)->nullable();
            $table->string('telefono3',30)->nullable();
            $table->string('observacion',250)->nullable();
            $table->string('req_paciente',2)->nullable();
            $table->double('porcent_nino', 9, 3)->default(0);
            $table->string('cuit',13)->nullable();
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
        Schema::dropIfExists('cpps07s');
    }
};
