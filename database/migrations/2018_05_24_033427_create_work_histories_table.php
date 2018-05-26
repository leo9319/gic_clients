<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->date('job_start')->nullable();
            $table->date('job_end')->nullable();
            $table->string('work_hours')->nullable();
            $table->string('noc')->nullable();
            $table->string('job_title')->nullable();
            $table->string('employer_name')->nullable();
            $table->string('job_in_country')->nullable();
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
        Schema::dropIfExists('work_histories');
    }
}
