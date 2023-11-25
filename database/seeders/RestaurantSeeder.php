<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::table('restaurants')->insert([
            'name_eng' => 'Rest 1',
            'name_ar' => 'مطعم 1',
            'sender_name' => 'UNISMS',
            'number_of_messages' => 100,
            'reference_id' => '555866',

            'email' => 'rest1@gmail.com',
            'password' => Hash::make('123456'),
        ]);

    }
}
