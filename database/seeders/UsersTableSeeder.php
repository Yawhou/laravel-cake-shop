<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email'=>'admin@gmail.com',
            'name'=>'admin',
            'phone_number'=>'12345678910',
            'role_as'=>'1', //admin
            'password'=>Hash::make('123456')  //will use bcrypt for hashing, as set in config/hashing.php
        ]);
        User::create([
            'email'=>'arpita@gmail.com',
            'name'=>'arpita',
            'phone_number'=>'12345678910',
            'role_as'=>'0', //normal user
            'password'=>Hash::make('123456')
        ]);

        // Use the definition provided by UserFactory to create 3 fake user records
        User::factory()->count(3)->create();
    }
}
