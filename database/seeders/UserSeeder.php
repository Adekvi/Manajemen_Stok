<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'id' => '1',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('1'),
                'role' => 'admin',
            ],
            [
                'id' => '2',
                'username' => 'User test',
                'email' => 'usertest@gmail.com',
                'password' => Hash::make('1'),
                'role' => 'user',
            ]
        ];
        foreach ($users as $key => $user) {
            User::create($user);
        }
    }
}
