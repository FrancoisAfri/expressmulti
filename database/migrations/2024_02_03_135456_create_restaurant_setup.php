<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantSetup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_setup', function (Blueprint $table) {
            $table->id();
			$table->uuid('uuid')->index();
			$table->string('colour_one')->nullable();
			$table->string('colour_two')->nullable();
			$table->string('colour_three')->nullable();
			$table->string('mins_one')->nullable();
			$table->string('mins_two')->nullable();
			$table->string('mins_three')->nullable();
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
        Schema::dropIfExists('restaurant_setup');
    }
}
