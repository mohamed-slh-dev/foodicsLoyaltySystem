<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_ranks', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();

            $table->string('times')->nullable();

             //if local select the branch requiered
             $table->string('localization')->nullable();

             //related to branch null or branch
             $table->string('related_to_branch')->nullable();

            $table->string('branch_name')->nullable();

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
        Schema::dropIfExists('restaurant_ranks');
    }
}
