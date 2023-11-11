<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsPersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts_person', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
			$table->integer('company_id')->nullable();
			$table->integer('status')->nullable();
			$table->string('first_name')->nullable();
			$table->string('surname')->nullable();
			$table->bigInteger('date_of_birth')->nullable();
			$table->string('email')->unique()->nullable();
            $table->string('contact_number')->nullable();
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
        Schema::dropIfExists('contacts_person');
    }
}
