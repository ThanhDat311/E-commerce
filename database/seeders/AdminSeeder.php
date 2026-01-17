<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo tài khoản Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@electro.com',
            'password' => Hash::make('password123'), // Mật khẩu mẫu
            'role' => 'admin', // Quan trọng nhất là dòng này
            'email_verified_at' => now(),
        ]);
        
        // Tạo thêm 1 user thường để test
        User::create([
            'name' => 'Normal Customer',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);
    }
}