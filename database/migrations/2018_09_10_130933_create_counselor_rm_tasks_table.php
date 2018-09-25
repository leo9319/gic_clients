<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCounselorRmTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('counselor_rm_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_id');
            $table->string('user_id');
            $table->string('step_id');
            $table->string('task_id');
            $table->date('deadline');
            $table->date('form_entry_id');
            $table->string('uploaded_file_name')->nullable();
            $table->string('status');
            $table->integer('approved_by');
            $table->integer('priority');
            $table->boolean('approval');
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
        Schema::dropIfExists('counselor_rm_tasks');
    }
}
