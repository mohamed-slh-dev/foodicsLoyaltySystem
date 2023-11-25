<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::table('users')->insert([
            'name' => 'Admin admin',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'password' => Hash::make('123456'),
        ]);

    }
}
