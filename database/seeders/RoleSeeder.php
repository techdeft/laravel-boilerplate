<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $role = Role::create(['name' => 'admin']);
        $role = Role::create(['name' => 'pharmacist']);
        $role = Role::create(['name' => 'user']);



        $user = User::create([
            'uuid' => (string) Str::uuid(),
            'name' => 'Admin',
            'email' => 'admin@medmall.ng',
            'password' => bcrypt('password'),
            'phone' => '08000000000',
            'status' => 'active',
            'phone_verified_at' => now(),
            'email_verified_at' => now(),

        ]);

        $user->assignRole('admin');
    }
}
