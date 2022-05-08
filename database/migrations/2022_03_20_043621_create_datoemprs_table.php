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
        Schema::create('datoemprs', function (Blueprint $table) {
            $table->id();
            $table->string('razon',35)->nullable();
            $table->string('nomcom',35)->nullable();
            $table->string('domicilio',35)->nullable();
            $table->string('prov',2)->nullable();
            $table->string('ibnumero',25)->nullable();
            $table->integer('interno')->nullable();
            $table->string('tipo',30)->nullable();
            $table->string('provincia',2)->nullable();
            $table->string('localidad',35)->nullable();
            $table->string('cp',20)->nullable();
            $table->string('tel1',20)->nullable();
            $table->string('tel2',20)->nullable();
            $table->string('fax',20)->nullable();
            $table->string('cel',20)->nullable();
            $table->string('email',30   )->nullable();
            $table->string('web',30)->nullable();
            $table->string('condiva',30)->nullable();
            $table->string('cuit',13)->nullable();
            $table->string('condib',15)->nullable();
            $table->string('numib',20)->nullable();
            $table->string('actividad',35)->nullable();
            $table->string('actividad2',35)->nullable();
            $table->integer('tipoem')->nullable();
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
        Schema::dropIfExists('datoemprs');
    }
};
