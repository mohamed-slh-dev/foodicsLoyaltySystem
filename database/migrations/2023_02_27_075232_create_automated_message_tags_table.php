<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutomatedMessageTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('automated_message_tags', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('automated_message_id')->unsigned()->nullable();
            $table->foreign('automated_message_id')->references('id')->on('automated_messages');

            $table->bigInteger('tag_id')->unsigned()->nullable();
            $table->foreign('tag_id')->references('id')->on('tags');

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
        Schema::dropIfExists('automated_message_tags');
    }
}
