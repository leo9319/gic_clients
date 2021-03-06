<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomeExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id');
            $table->string('payment_type');
            $table->integer('total_amount');
            $table->string('bank_name');
            $table->string('location')->nullable();
            $table->tinyInteger('recheck');
            $table->text('description');
            $table->string('advance_payment');
            $table->integer('cleared_amount')->default(0);
            $table->enum('advance_payment', ['yes', 'no']);
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
        Schema::dropIfExists('income_expenses');
    }
}
