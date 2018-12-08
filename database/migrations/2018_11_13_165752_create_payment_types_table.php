<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('payment_id');
            $table->string('payment_type');
            $table->string('card_type');
            $table->string('name_on_card');
            $table->string('card_number');
            $table->string('expiry_date');
            $table->string('pos_machine');
            $table->string('approval_code');
            $table->string('bank_name');
            $table->string('cheque_number');
            $table->string('phone_number', 15);
            $table->decimal('bank_charge', 4,2);
            $table->integer('amount_paid');
            $table->decimal('amount_received', 10,2);
            $table->date('deposit_date')->nullable();
            $table->tinyInteger('cheque_verified');
            $table->tinyInteger('due_payment')->default(0);
            $table->tinyInteger('refund_payment')->default(0);
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
        Schema::dropIfExists('payment_types');
    }
}
