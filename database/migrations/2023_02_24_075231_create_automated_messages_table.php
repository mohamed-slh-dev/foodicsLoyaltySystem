<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutomatedMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('automated_messages', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('description')->nullable();

            //one time or campaign
            $table->string('type')->nullable();

            //sms or email
            $table->string('message_type')->nullable();

            $table->string('body')->nullable();

             //send after in  hours
             $table->integer('send_after')->nullable();

            $table->bigInteger('restaurant_id')->unsigned()->nullable();
            $table->foreign('restaurant_id')->references('id')->on('restaurants');

            
            $table->boolean('is_deleted')->default(false);

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
        Schema::dropIfExists('automated_messages');
    }
}
