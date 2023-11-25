<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_users', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('username')->nullable();
            $table->string('email')->nullable();
            $table->string('access_level')->nullable();

            $table->boolean('general_update')->default(true);
            $table->boolean('general_update_integration')->default(true);

            $table->boolean('auto_tag_create')->default(true);
            $table->boolean('auto_tag_update')->default(true);

            $table->boolean('email_campagin_create')->default(true);
            $table->boolean('email_campagin_update')->default(true);

            $table->boolean('sms_campagin_create')->default(true);
            $table->boolean('sms_campagin_update')->default(true);

            $table->boolean('promocode_campagin_create')->default(true);
            $table->boolean('promocode_campagin_update')->default(true);

            $table->boolean('reports_access')->default(true);

            $table->boolean('guest_create')->default(true);
            $table->boolean('guest_update')->default(true);

            $table->boolean('access_create')->default(true);
            $table->boolean('access_update')->default(true);


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
        Schema::dropIfExists('restaurant_users');
    }
}
