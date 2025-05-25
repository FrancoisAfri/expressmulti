<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToCompanyIdentiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_identies', function (Blueprint $table) {
            $table->string('admin_email')->unsigned()->nullable();
            $table->string('debit_order_form')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_identies', function (Blueprint $table) {
            $table->bigInteger('admin_email')->unsigned()->nullable();
            $table->bigInteger('debit_order_form')->unsigned()->nullable();
        });
    }
}
