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
        Schema::create('cpps30s', function (Blueprint $table) {
            $table->id();
            $table->string('periodo',7)->nullable();
            $table->Integer('cod_os');
            $table->Integer('mat_prov_cole')->nullable();
            $table->string('ordennro', 20)->nullable();
            $table->integer('dni_afiliado')->nullable();
            $table->string('nom_afiliado', 50)->nullable();
            $table->date('fecha')->nullable();
            $table->unsignedBigInteger('plan');          // cod_conv en cpps11 as foreign key
            $table->integer('id_nomen')->nullable();        // 1
            $table->string('cod_nomen',15)->nullable();     // 330101
            $table->smallInteger('cantidad')->nullable();
            $table->float('precio')->nullable();
            $table->double('importe')->nullable();
            $table->timestamps();

            // FK
            $table->foreign('plan')->references('cod_conv')->on('cpps11s')->onDelete('cascade');
            //$table->foreign('cod_os')->references('cod_os')->on('cpps07s')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cpps30s');
    }
};
