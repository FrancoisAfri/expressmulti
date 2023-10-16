<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->string('title')->nullable();
            $table->string('first_name')->nullable();
            $table->string('initial')->nullable();
            $table->string('surname')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('cell_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('fax_number')->nullable();
            $table->string('id_number')->nullable();
            $table->string('res_address')->nullable();
            $table->string('post_address')->nullable();
            $table->string('res_suburb')->nullable();
            $table->string('res_city')->nullable();
            $table->integer('res_postal_code')->nullable();
            $table->integer('res_province_id')->nullable();
            $table->bigInteger('date_of_birth')->nullable();
            $table->bigInteger('date_joined')->nullable();
            $table->smallInteger('gender')->nullable()->default(0);
            $table->string('profile_pic')->nullable();
            $table->string('age')->nullable();
            $table->boolean('is_active')->nullable()->default(1);
            $table->string('occupation')->nullable()->default(1);
            $table->string('postal_address')->nullable();
            $table->string('postal_suburb')->nullable();
            $table->string('postal_city')->nullable();
            $table->string('postal_postal_code')->nullable();
            $table->integer('postal_province_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->integer('payment_status')->nullable();
            $table->string('highest_schooling')->nullable();
            $table->string('tertiary_qualification')->nullable();
            $table->string('other_skills')->nullable();
            $table->string('home_language')->nullable();
            $table->string('other_language')->nullable();
            $table->string('other_organization')->nullable();
			$table->integer('Province_id')->nullable();
            $table->integer('region')->nullable();
            $table->integer('branch')->nullable();
            $table->integer('ward')->nullable();
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
        Schema::dropIfExists('companies');
    }
}
