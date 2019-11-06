<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColDateTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_park_histories', function (Blueprint $table) {

            if (Schema::hasColumn('car_park_histories', 'date_time')) {
                $table->dropColumn('date_time');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_park_histories', function (Blueprint $table) {
            //
        });
    }
}
