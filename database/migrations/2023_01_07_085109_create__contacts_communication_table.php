<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsCommunicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts_communication', function (Blueprint $table) {
            $table->id();
            $table->integer('communication_type')->unsigned()->index()->nullable();
            $table->integer('company_id')->unsigned()->index()->nullable();
            $table->string('message')->nullable();
            $table->integer('status')->nullable();
            $table->integer('sent_by')->nullable();
            $table->bigInteger('communication_date')->nullable();
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
        Schema::dropIfExists('contacts_communication');
    }
}
