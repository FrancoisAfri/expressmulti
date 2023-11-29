<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaiterAuditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('waiter_audits', function (Blueprint $table) {
            $table->id();
			$table->uuid('uuid')->index();
			$table->bigInteger('table_id')->nullable();
			$table->bigInteger('employee_id')->nullable();
			$table->bigInteger('user_id')->nullable();
			$table->string('comment')->nullable();
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
        Schema::dropIfExists('waiter_audits');
    }
}
