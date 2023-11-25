<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('restaurant_discount_id')->unsigned()->nullable();
            $table->foreign('restaurant_discount_id')->references('id')->on('restaurant_discounts');

            $table->bigInteger('automated_message_id')->unsigned()->nullable();
            $table->foreign('automated_message_id')->references('id')->on('automated_messages');

            $table->string('code')->nullable();

            $table->string('customer_phone')->nullable();

            $table->integer('using_times')->nullable();
            $table->integer('used_times')->nullable()->default(0);
            
            $table->double('amount',  8, 2)->nullable();

            $table->double('discount_amount',  8, 2)->nullable();

            $table->double('reward_discount',  8, 2)->nullable();

            $table->text('order_id')->nullable();

            $table->boolean('is_deleted')->default(0);
            

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
        Schema::dropIfExists('discount_codes');
    }
}
