<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientFileInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_file_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id');
            $table->integer('creator_id');
            $table->string('spouse_name')->nullable();
            $table->text('address');
            $table->string('country_of_choice');
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
        Schema::dropIfExists('client_file_infos');
    }
}
