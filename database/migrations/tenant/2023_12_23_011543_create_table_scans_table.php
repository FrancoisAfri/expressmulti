<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableScansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_scans', function (Blueprint $table) {
            $table->id();
			$table->uuid('uuid')->index();
			$table->string('ip_address')->nullable();
			$table->bigInteger('status')->nullable();
			$table->bigInteger('table_id')->nullable();
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
        Schema::dropIfExists('table_scans');
    }
}
