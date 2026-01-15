<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = DB::table('permissions')->pluck('id', 'slug');

        $rolePermissions = [];

        // ADMIN: all permissions
        foreach ($permissions as $permissionId) {
            $rolePermissions[] = [
                'role_id' => 1,
                'permission_id' => $permissionId,
            ];
        }

        // STAFF
        $staffPermissions = [
            'product.view',
            'product.create',
            'product.update',
            'order.view',
            'order.update',
            'report.view',
        ];

        foreach ($staffPermissions as $slug) {
            $rolePermissions[] = [
                'role_id' => 2,
                'permission_id' => $permissions[$slug],
            ];
        }

        // CUSTOMER
        $customerPermissions = [
            'product.view',
            'order.view',
        ];

        foreach ($customerPermissions as $slug) {
            $rolePermissions[] = [
                'role_id' => 3,
                'permission_id' => $permissions[$slug],
            ];
        }

        DB::table('role_permissions')->insert($rolePermissions);
    }
}
