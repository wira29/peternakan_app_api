<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'id' => Str::uuid(),
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('Password123!'),
        ]);

        $ownerUser = User::factory()->create([
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'password' => Hash::make('Password123!')
        ]);

        $ownerUser->assignRole('owner');

        $supplierUser = User::factory()->create([
            'name' => 'Supplier',
            'email' => 'supplier@example.com',
            'password' => Hash::make('Password123!')
            
        ]);
        $supplierUser->assignRole('supplier');

        $adminUser = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('Password123!')
            
        ]);
        $adminUser->assignRole('admin');
    }
}
