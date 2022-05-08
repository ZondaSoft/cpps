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
        Schema::create('stk002s', function (Blueprint $table) {
            $table->id();
            $table->string('codigo',15);
            $table->string('detalle',50);
            $table->string('descri',50)->nullable();
            $table->string('unidad',3)->nullable();
            $table->float('costo')->nullable();
            $table->float('reposicion')->nullable();
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
        Schema::dropIfExists('stk002s');
    }
};
