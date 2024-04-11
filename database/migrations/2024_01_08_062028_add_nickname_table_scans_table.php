<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNicknameTableScansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('table_scans', function ($table) {
            $table->string('nickname')->unsigned()->nullable();
            $table->string('comment')->unsigned()->nullable();
			$table->bigInteger('scan_time')->unsigned()->nullable();
			$table->bigInteger('closed_time')->unsigned()->nullable();
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
            $table->dropColumn('nickname');
            $table->dropColumn('comment');
            $table->dropColumn('scan_time');
            $table->dropColumn('closed_time');
        });
    }
}
