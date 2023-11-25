<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestFavCombosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_fav_combos', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('guest_id')->unsigned()->nullable();
            $table->foreign('guest_id')->references('id')->on('guests');

            $table->string('combo_name')->nullable();

            $table->string('combo_id')->nullable();

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
        Schema::dropIfExists('guest_fav_combos');
    }
}
