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
        Schema::create('cpps09s', function (Blueprint $table) {
            $table->increments('id_nomen');
            $table->Integer('id_raiz')->default(0);
            $table->Integer('orden')->default(0);
            $table->string('cod_nomen',15)->nullable();
            $table->string('cod_nemotecnico',10)->nullable();
            $table->string('nom_prest',50)->nullable();
            $table->string('observacion',200)->nullable();
            $table->string('desc_variante',50)->nullable();
            $table->smallInteger('estado_nomen')->default(0);
            $table->boolean('ips');
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
        Schema::dropIfExists('cpps09s');
    }
};
