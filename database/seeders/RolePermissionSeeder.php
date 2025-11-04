<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $permissions = [
            'manage-users',
            'manage-locations',
            'manage-materials',
            'manage-feeds',
            'manage-orders-materials',
            'manage-blend-materials',
            'sale-feeds',
            'manage-cages',
            'manage-breeds',
            'manage-goats',
            'view-vaccine-records',
            'view-weight-records',
            'view-mating-records',
            'feeding',
            'sale-goats',
            'view-sale-reports',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'api']);
        }

        $roles = [
            'owner',
            'supplier',
            'admin',
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role, 'guard_name' => 'api']);
        }

        $owner = Role::findByName('owner', 'api');
        $owner->syncPermissions(['manage-users', 'manage-locations']);
        $supplier = Role::findByName('supplier', 'api');
        $supplier->givePermissionTo(['manage-materials', 'manage-feeds', 'manage-orders-materials', 'manage-blend-materials', 'sale-feeds']);
        $admin = Role::findByName('admin', 'api');
        $admin->givePermissionTo(['manage-cages', 'manage-breeds', 'manage-goats', 'view-vaccine-records', 'view-weight-records', 'view-mating-records', 'feeding', 'sale-goats', 'view-sale-reports']);
    }
}
