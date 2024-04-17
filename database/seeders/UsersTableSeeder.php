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
            'email'=>'admin@email.com',
            'name'=>'admin',
            'phone_number'=>'+60123456789',
            'role_as'=>'1', //admin
            'password'=>Hash::make('qwerty')  //will use bcrypt for hashing, as set in config/hashing.php
        ]);
        User::create([
            'email'=>'user@email.com',
            'name'=>'user',
            'phone_number'=>'+60123456789',
            'role_as'=>'2', //normal user
            'password'=>Hash::make('qwerty')
        ]);

        // Use the definition provided by UserFactory to create 3 fake user records
        User::factory()->count(3)->create();
    }
}
