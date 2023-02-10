<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    private const USERS = [
        [
            'name' => 'Booker Joe',
            'email' => 'booking@example.com',
            'password' => '123456'
        ]
    ];

    public function run()
    {
        foreach (self::USERS as $user) {
            $user['password'] = Hash::make($user['password']);
            DB::table('users')->insert($user);
        }
    }
}
