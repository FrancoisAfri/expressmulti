<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies_temp', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->string('name')->nullable();
            $table->string('trading_as')->nullable();
            $table->string('vat')->nullable();
			$table->string('email')->unique()->nullable();
            $table->string('cell_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('res_address')->nullable();
            $table->string('post_address')->nullable();
            $table->bigInteger('date_joined')->nullable();
            $table->string('client_logo')->nullable();
            $table->boolean('is_active')->nullable()->default(1);
            $table->string('payment_method')->nullable();
            $table->integer('package_id')->nullable();
            $table->integer('payment_status')->nullable();
            $table->string('database_name')->nullable();
            $table->string('database_user')->nullable();
            $table->string('database_password')->nullable();
            $table->string('tenant_url')->nullable();
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
        Schema::dropIfExists('companies_temp');
    }
}
