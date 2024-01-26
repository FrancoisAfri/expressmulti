<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events_services', function (Blueprint $table) {
            $table->id();
			$table->uuid('uuid')->index();
			$table->bigInteger('item_id')->nullable();
			$table->bigInteger('table_id')->nullable();
			$table->bigInteger('scan_id')->nullable();
			$table->bigInteger('service_type')->nullable();
			$table->bigInteger('requested_time')->nullable();
			$table->bigInteger('completed_time')->nullable();
			$table->string('service', 5000)->nullable();
			$table->bigInteger('status')->nullable();
			$table->string('comment', 5000)->nullable();
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
        Schema::dropIfExists('events_services');
    }
}
