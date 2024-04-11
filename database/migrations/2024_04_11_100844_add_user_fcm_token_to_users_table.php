<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserFcmTokenToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {		
		Schema::table('users', function ($table) {
            $table->string('user_fcm_token')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {		
		Schema::table('users', function ($table) {
            $table->dropColumn('user_fcm_token');
        });
    }
}
