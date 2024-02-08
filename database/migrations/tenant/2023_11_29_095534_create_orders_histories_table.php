<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_histories', function (Blueprint $table) {
            $table->id();
			$table->uuid('uuid')->index();
			$table->string('comment')->nullable();
			$table->string('action')->nullable();
			$table->bigInteger('table_id')->nullable();
			$table->bigInteger('order_id')->nullable();
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
        Schema::dropIfExists('orders_histories');
    }
}
