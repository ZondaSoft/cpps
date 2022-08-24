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
        Schema::table('cpps30s', function (Blueprint $table) {
            //
            $table->foreign('mat_prov_cole')->references('mat_prov_cole')->on('cpps01s');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cpps30s', function (Blueprint $table) {
            //
        });
    }
};
