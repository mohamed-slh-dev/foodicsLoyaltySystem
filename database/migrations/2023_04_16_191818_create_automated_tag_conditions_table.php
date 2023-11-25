<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutomatedTagConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('automated_tag_conditions', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('automated_tag_id')->unsigned()->nullable();
            $table->foreign('automated_tag_id')->references('id')->on('automated_tags');

            $table->string('condition_type')->nullable();

            $table->string('condition')->nullable();

            $table->string('type')->nullable();

            
            //range from and to
            $table->integer('range_from')->nullable()->default(0);
            $table->integer('range_to')->nullable()->default(0);


            //ordered item times
            $table->integer('times')->nullable()->default(0);


             //product reference from foodic
             $table->string('product_id')->nullable();

             //order category
            $table->string('category_reference')->nullable();

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
        Schema::dropIfExists('automated_tag_conditions');
    }
}
