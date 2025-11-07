<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'username' => 'admin',
            'name_user' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $user = User::create([
            'username' => 'waiter',
            'name_user' => 'waiter',
            'password' => Hash::make('password'),
            'role' => 'waiter',
        ]);

        $user = User::create([
            'username' => 'kasir',
            'name_user' => 'kasir',
            'password' => Hash::make('password'),
            'role' => 'kasir',
        ]);

        $user = User::create([
            'username' => 'owner',
            'name_user' => 'owner',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);
    }
}
