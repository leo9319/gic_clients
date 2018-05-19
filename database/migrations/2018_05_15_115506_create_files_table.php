<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('programs');
            $table->string('visa_type');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('mobile');
            $table->date('dob');
            $table->string('email');
            $table->string('marital_status');
            $table->string('education');
            $table->string('university_attended');
            $table->string('profession');
            $table->string('work_experience');
            $table->string('field_of_work');
            $table->string('hear_about_us');
            $table->boolean('foreign_country_visited');
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
        Schema::dropIfExists('files');
    }
}
