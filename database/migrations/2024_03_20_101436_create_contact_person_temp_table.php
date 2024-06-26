<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactPersonTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_person_temp', function (Blueprint $table) {
            $table->id();
			$table->uuid('uuid')->index();
            $table->integer('company_id')->nullable();
            $table->integer('status')->nullable();
            $table->string('first_name')->nullable();
            $table->string('surname')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
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
        Schema::dropIfExists('contact_person_temp');
    }
}
