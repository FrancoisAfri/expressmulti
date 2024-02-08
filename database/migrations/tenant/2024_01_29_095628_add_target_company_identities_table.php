<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTargetCompanyIdentitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_identies', function ($table) {
            $table->double('monthly_revenue_target')->unsigned()->nullable();
            $table->double('daily_revenue_target')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_identies', function ($table) {
            $table->dropColumn('monthly_revenue_target');
            $table->dropColumn('daily_revenue_target');
        });
    }
}
