<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_products', function (Blueprint $table) {
            $table->id();
			$table->uuid('uuid')->index();
			$table->string('comment')->nullable();
			$table->bigInteger('status')->nullable();
			$table->bigInteger('product_id')->nullable();
			$table->bigInteger('table_id')->nullable();
			$table->bigInteger('order_id')->nullable();
			$table->double('price')->nullable();
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
        Schema::dropIfExists('orders_products');
    }
}
