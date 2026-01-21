<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'id' => 1,
                'name' => 'Admin',
                'description' => 'System Administrator',
            ],
            [
                'id' => 2,
                'name' => 'Staff',
                'description' => 'Staff / Operator',
            ],
            [
                'id' => 3,
                'name' => 'Customer',
                'description' => 'End User / Buyer',
            ],
            [
                'id' => 4,
                'name' => 'Vendor',
                'description' => 'Shop Owner / Seller'
            ],
        ]);
    }
}
