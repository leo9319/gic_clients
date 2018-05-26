<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEducationHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('education_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('field_of_study')->nullable();
            $table->date('edu_start')->nullable();
            $table->date('edu_end')->nullable();
            $table->string('complete_years')->nullable();
            $table->string('full_or_part')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('school')->nullable();
            $table->string('level')->nullable();
            $table->string('degree_canada')->nullable();
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
        Schema::dropIfExists('education_histories');
    }
}
