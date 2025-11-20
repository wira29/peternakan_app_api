<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'no_telp' => '081234567890',
            'password' => bcrypt('Password123!'),
        ]);

        $ownerUser = User::factory()->create([
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'no_telp' => '081234567890',
            'password' => Hash::make('Password123!')
        ]);

        $ownerUser->assignRole('owner');

        $supplierUser = User::factory()->create([
            'name' => 'Supplier',
            'email' => 'supplier@example.com',
            'no_telp' => '081234567890',
            'password' => Hash::make('Password123!')
            
        ]);
        $supplierUser->assignRole('supplier');

        $adminUser = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'no_telp' => '081234567890',
            'password' => Hash::make('Password123!'),
            'location_id' => 'a1b2c3d4-1111-2222-3333-a1b2c3d4e5f6'
            
        ]);
        $adminUser->assignRole('admin');
    }
}
