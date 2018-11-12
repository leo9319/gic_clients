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
            $table->integer('client_id');
            $table->integer('program_id');
            $table->integer('step_id');
            $table->string('card_type')->nullable();
            $table->string('name_on_card')->nullable();
            $table->string('card_number')->nullable();
            $table->string('expiry_date')->nullable();
            $table->string('approval_code')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('cheque_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->integer('opening_fee');
            $table->integer('embassy_student_fee');
            $table->integer('service_solicitor_fee');
            $table->integer('other');
            $table->integer('total_amount');
            $table->integer('amount_paid');
            $table->decimal('bank_charges', 4,4)->default(0);
            $table->integer('total_after_charge')->nullable();
            $table->tinyInteger('cheque_verified')->default(-1);
            $table->tinyInteger('recheck')->default(0);
            $table->date('due_clearance_date')->nullable;
            $table->text('description');
            $table->integer('created_by');
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
