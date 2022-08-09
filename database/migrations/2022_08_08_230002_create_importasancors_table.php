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
        Schema::create('importasancors', function (Blueprint $table) {
            $table->id();
            $table->Integer('nro_convenio')->nullable();
            $table->string('prestador',70)->nullable();
            $table->Integer('nro_efector')->nullable();
            $table->string('nom_efector',70)->nullable();
            $table->Integer('mat_prov_cole')->nullable();
            $table->Integer('tipo_orden')->nullable();
            $table->string('ordennro', 20)->nullable();
            $table->date('fecha')->nullable();
            $table->string('nro_afiliado',70)->nullable();
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
        Schema::dropIfExists('importasancors');
    }
};
