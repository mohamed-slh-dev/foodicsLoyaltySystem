<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_orders', function (Blueprint $table) {
            $table->id();

            $table->string('timestamp')->nullable();
            $table->string('order_id')->nullable();
            $table->string('event')->nullable();

            $table->string('reference_id')->nullable();
            $table->string('branch_id')->nullable();
            $table->string('branch_name')->nullable();

       
            $table->string('order_count')->nullable();

            
            $table->double('amount', 8, 2)->nullable();

            $table->double('total_amount',  8, 2)->nullable();
           

            $table->bigInteger('discount_code_id')->unsigned()->nullable();
            $table->foreign('discount_code_id')->references('id')->on('discount_codes');

            $table->bigInteger('guest_id')->unsigned()->nullable();
            $table->foreign('guest_id')->references('id')->on('guests');

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
        Schema::dropIfExists('guest_orders');
    }
}
