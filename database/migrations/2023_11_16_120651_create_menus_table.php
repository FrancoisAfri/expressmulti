<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
			$table->uuid('uuid')->index();
			$table->string('name')->nullable();
			$table->string('description')->nullable();
			$table->string('ingredients', 3000)->nullable();
			$table->string('image')->nullable();
			$table->string('video')->nullable();
			$table->string('menu_docs')->nullable();
			$table->bigInteger('category_id')->nullable();
			$table->bigInteger('menu_type')->nullable();
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
        Schema::dropIfExists('menus');
    }
}
