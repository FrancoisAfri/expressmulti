<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCloseRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('close_requests', function (Blueprint $table) {
            $table->id();
			$table->uuid('uuid')->index();
			$table->bigInteger('table_id')->nullable();
			$table->bigInteger('scan_id')->nullable();
			$table->bigInteger('status')->nullable();
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
        Schema::dropIfExists('close_requests');
    }
}
