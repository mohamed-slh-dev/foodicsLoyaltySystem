<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class RestaurantUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('restaurant_users')->insert([
            'name' => 'admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'access_level' => 'admin',

            'general_update' => 1,
            'general_update_integration' => 1,

            'auto_tag_create' => 1,
            'auto_tag_update' => 1,

            'email_campagin_create' => 1,
            'email_campagin_update' => 1,

            'sms_campagin_create' => 1,
            'sms_campagin_update' => 1,

            'promocode_campagin_create' => 1,
            'promocode_campagin_update' => 1,

            'reports_access' => 1,


            'guest_create' => 1,
            'guest_update' => 1,

            'access_create' => 1,
            'access_update' => 1,

            'restaurant_id' => 1,
        ]);
    }
}
