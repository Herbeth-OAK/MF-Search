<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Herbeth Lucas',
            'nickname' => 'lukas-ADM',
            'email' => 'lukas-adm@example.com',
            'password' => Hash::make('12345678'),
        ]);
    }
}
