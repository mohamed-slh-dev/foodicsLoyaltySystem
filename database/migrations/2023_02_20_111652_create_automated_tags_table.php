<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutomatedTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('automated_tags', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('tag_id')->unsigned()->nullable();
            $table->foreign('tag_id')->references('id')->on('tags');


            $table->bigInteger('based_on_tag_id')->unsigned()->nullable();
            $table->foreign('based_on_tag_id')->references('id')->on('tags');

            $table->bigInteger('based_on_rank_id')->unsigned()->nullable();
            $table->foreign('based_on_rank_id')->references('id')->on('restaurant_ranks');

            //if local select the branch requiered
            $table->string('localization')->nullable();

            //related to branch null or branch
            $table->string('related_to_branch')->nullable();

            //total visits - total order - total spent - avg spent per visit - last visit - order item - order combo
            $table->string('type')->nullable();

            //is is_recurring
            $table->string('is_recurring')->nullable()->default('false');


            //range from and to
            $table->integer('range_from')->nullable()->default(0);
            $table->integer('range_to')->nullable()->default(0);


            //ordered item times
            $table->integer('times')->nullable()->default(0);


             //product reference from foodic
             $table->string('product_id')->nullable();

             //order category
            $table->string('category_reference')->nullable();

            $table->string('has_conditions')->default('no');

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
        Schema::dropIfExists('automated_tags');
    }
}
