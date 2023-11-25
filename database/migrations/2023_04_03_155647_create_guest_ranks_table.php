<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestRanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_ranks', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('guest_id')->unsigned()->nullable();
            $table->foreign('guest_id')->references('id')->on('guests');

            $table->bigInteger('rank_id')->unsigned()->nullable();
            $table->foreign('rank_id')->references('id')->on('restaurant_ranks');

            $table->string('is_valid')->default('true');
            
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
        Schema::dropIfExists('guest_ranks');
    }
}
