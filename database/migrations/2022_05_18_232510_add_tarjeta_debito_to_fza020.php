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
        Schema::table('fza020s', function (Blueprint $table) {
            $table->decimal('tarjetadebito',11,2)->default(0);
            $table->decimal('tarjetacredito',11,2)->default(0);
            $table->decimal('bancarios',11,2)->default(0);
            $table->decimal('cheques',11,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fza020', function (Blueprint $table) {
            //
        });
    }
};
