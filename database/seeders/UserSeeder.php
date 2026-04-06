<?php

namespace Database\Seeders;

use App\Models\Role;
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
        // ambil role
        $adminRole = Role::where('name', 'admin')->first();
        $userRole  = Role::where('name', 'user')->first();

        $users = [
            [
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('1'),
                'role' => $adminRole,
            ],
            [
                'username' => 'User test',
                'email' => 'usertest@gmail.com',
                'password' => Hash::make('1'),
                'role' => $userRole,
            ]
        ];

        foreach ($users as $data) {

            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'username' => $data['username'],
                    'password' => $data['password'],
                ]
            );

            if (!$data['role']) {
                throw new \Exception("Role tidak ditemukan: {$data['username']}");
            }

            // 💥 HAPUS dulu semua role lama (biar gak nyangkut)
            $user->roles()->detach();

            // baru assign
            $user->roles()->attach($data['role']->id);
        }
    }
}
