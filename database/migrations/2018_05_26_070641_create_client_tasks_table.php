<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_id');
            $table->string('task_id');
            $table->string('assignee_id');
            $table->date('assigned_date');
            $table->string('uploaded_file_name')->nullable();
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
        Schema::dropIfExists('client_tasks');
    }
}
