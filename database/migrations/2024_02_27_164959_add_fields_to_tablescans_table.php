<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToTablescansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('table_scans', function ($table) {
            $table->string('q_one')->nullable();
            $table->string('q_two')->nullable();
            $table->string('q_three')->nullable();
            $table->string('q_four')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('table_scans', function ($table) {
            $table->dropColumn('q_one');
            $table->dropColumn('q_two');
            $table->dropColumn('q_three');
            $table->dropColumn('q_four');
        });
    }
}
