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
        Schema::table('importasancors', function (Blueprint $table) {
            $table->string('nom_afiliado', 50)->nullable();
            $table->string('desc_plan', 50)->nullable();
            $table->string('nomenclador', 2)->nullable();
            $table->string('cod_nomen',15)->nullable();
            $table->string('desc_nomen',50)->nullable();
            $table->smallInteger('cantidad')->nullable();
            $table->float('precio')->nullable();
            $table->double('importe')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('importasancors', function (Blueprint $table) {
            //
        });
    }
};
