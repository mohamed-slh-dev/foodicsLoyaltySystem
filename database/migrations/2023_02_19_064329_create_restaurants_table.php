<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();

            $table->string('name_eng')->nullable();
            $table->string('name_ar')->nullable();
            $table->string('location')->nullable();
            $table->string('type')->nullable();

            $table->string('manager_name')->nullable();
            $table->string('manager_phone')->nullable();
            $table->string('manager_email')->nullable();

            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('has_branch')->nullable();

            $table->string('sender_name')->default('UNISMS');
            $table->integer('number_of_messages')->nullable()->default(0);

            $table->text('access_token')->nullable();

            $table->string('business_name')->nullable();
            $table->string('reference_id')->nullable();
            $table->string('business_id')->nullable();
            $table->string('owner_email')->nullable();

            $table->boolean('returntion')->default(false);
            $table->boolean('online_ordering_pickup')->default(false);
            $table->boolean('online_ordering_delivery')->default(false);

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
        Schema::dropIfExists('restaurants');
    }
}
