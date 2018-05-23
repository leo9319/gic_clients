<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdditionInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addition_info', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('email')->nullable();
            $table->string('question1')->nullable();
            $table->string('answer1')->nullable();
            $table->string('question2')->nullable();
            $table->string('answer2')->nullable();
            $table->string('question3')->nullable();
            $table->string('answer3')->nullable();
            $table->string('question4')->nullable();
            $table->string('answer4')->nullable();
            $table->string('gender')->nullable();
            $table->string('dob')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('country_ob')->nullable();
            $table->string('city_ob')->nullable();
            $table->string('passport_or_nid')->nullable();
            $table->string('document_id')->nullable();
            $table->string('document_number')->nullable();
            $table->string('country_of_issue')->nullable();
            $table->string('issue_date')->nullable();
            $table->string('expiry_date')->nullable();
            $table->string('immigration_before')->nullable();
            $table->string('citizenship')->nullable();
            $table->string('residence')->nullable();
            $table->string('family_members')->nullable();
            $table->string('relative')->nullable();
            $table->string('relation')->nullable();
            $table->string('relative_residence')->nullable();
            $table->string('primary_language')->nullable();
            $table->string('representative')->nullable();
            $table->string('rep_last_name')->nullable();
            $table->string('rep_first_name')->nullable();
            $table->string('rep_email')->nullable();
            $table->string('rep_id_number')->nullable();
            $table->string('nomination')->nullable();
            $table->string('noc_code')->nullable();
            $table->string('noc_start_date')->nullable();
            $table->string('certificate_of_qualification')->nullable();
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
        Schema::dropIfExists('addition_info');
    }
}
