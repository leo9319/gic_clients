<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('receipt_id')->nullable();
            $table->string('location')->default('dhaka');
            $table->integer('client_id');
            $table->integer('program_id');
            $table->integer('step_id');
            $table->integer('opening_fee');
            $table->integer('embassy_student_fee');
            $table->integer('service_solicitor_fee');
            $table->integer('other');
            $table->tinyInteger('recheck')->default(0);
            $table->integer('dues')->default(0);
            $table->date('due_date')->nullable();
            $table->date('due_cleared_date')->nullable();
            $table->text('description');
            $table->integer('created_by');
            $table->text('comments')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
