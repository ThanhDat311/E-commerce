<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // User
            ['slug' => 'user.view', 'name' => 'View users'],
            ['slug' => 'user.create', 'name' => 'Create user'],
            ['slug' => 'user.update', 'name' => 'Update user'],
            ['slug' => 'user.delete', 'name' => 'Delete user'],

            // Product
            ['slug' => 'product.view', 'name' => 'View products'],
            ['slug' => 'product.create', 'name' => 'Create product'],
            ['slug' => 'product.update', 'name' => 'Update product'],
            ['slug' => 'product.delete', 'name' => 'Delete product'],

            // Order
            ['slug' => 'order.view', 'name' => 'View orders'],
            ['slug' => 'order.update', 'name' => 'Update order status'],

            // Report
            ['slug' => 'report.view', 'name' => 'View reports'],
            ['slug' => 'report.export', 'name' => 'Export reports'],
        ];

        DB::table('permissions')->insert($permissions);
    }
}
