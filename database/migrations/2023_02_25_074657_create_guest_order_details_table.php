<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_order_details', function (Blueprint $table) {
            $table->id();

            

            $table->bigInteger('guest_order_id')->unsigned()->nullable();
            $table->foreign('guest_order_id')->references('id')->on('guest_orders');

            $table->string('type')->nullable();

            $table->string('combo_id')->nullable();
            $table->string('combo_sku')->nullable();
            $table->string('combo_name')->nullable();

            $table->string('product_id')->nullable();

            $table->string('product_sku')->nullable();

            $table->string('product_name')->nullable();

            $table->string('category_reference')->nullable();

            $table->string('category_name')->nullable();


            $table->string('product_price')->nullable();
          
            $table->string('quantity')->nullable();


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
        Schema::dropIfExists('guest_order_details');
    }
}
