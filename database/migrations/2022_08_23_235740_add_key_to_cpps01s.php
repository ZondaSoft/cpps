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
        Schema::table('cpps01s', function (Blueprint $table) {
            //
            //$table->integer('mat_prov_cole')->unique();
            $table->unique('mat_prov_cole');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cpps01s', function (Blueprint $table) {
            //
        });
    }
};
