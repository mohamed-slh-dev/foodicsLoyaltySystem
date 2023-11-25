<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnifonicMessageRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unifonic_message_records', function (Blueprint $table) {
            $table->id();


            $table->string('success')->nullable();
            $table->string('message')->nullable();
            $table->string('status')->nullable();
            $table->string('error_code')->nullable();

            $table->bigInteger('message_id')->nullable();
            $table->string('message_status')->nullable();
            $table->integer('number_of_units')->nullable();
            $table->integer('cost')->nullable();
            $table->integer('balance')->nullable();

            $table->string('recipient')->nullable();
            $table->string('time_created')->nullable();

            $table->bigInteger('restaurant_id')->unsigned()->nullable();
            $table->foreign('restaurant_id')->references('id')->on('restaurants');

            
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
        Schema::dropIfExists('unifonic_message_records');
    }
}
