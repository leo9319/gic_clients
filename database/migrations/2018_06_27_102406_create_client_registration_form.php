<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientRegistrationForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_registration_form', function (Blueprint $table) {
            $table->increments('id');
            $table->string('programs');
            $table->string('visa_types');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('mobile');
            $table->string('email');
            $table->date('dob');
            $table->string('marital_status');
            $table->string('education');
            $table->string('university');
            $table->string('profession');
            $table->string('experience');
            $table->string('field_of_work');
            $table->string('hear_about_us');
            $table->string('other_countries');
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
        Schema::dropIfExists('client_registration_form');
    }
}
