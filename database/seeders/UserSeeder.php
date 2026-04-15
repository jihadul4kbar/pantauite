<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin
        User::updateOrCreate(
            ['email' => 'admin@pantauite.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('admin123'),
                'must_change_password' => true,
                'role_id' => Role::superAdmin()->id,
                'employee_id' => 'EMP001',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // IT Manager
        User::updateOrCreate(
            ['email' => 'itmanager@pantauite.com'],
            [
                'name' => 'IT Manager',
                'password' => Hash::make('manager123'),
                'must_change_password' => true,
                'role_id' => Role::itManager()->id,
                'employee_id' => 'EMP002',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // IT Staff
        User::updateOrCreate(
            ['email' => 'itsupport@pantauite.com'],
            [
                'name' => 'IT Support Staff',
                'password' => Hash::make('staff123'),
                'must_change_password' => true,
                'role_id' => Role::itStaff()->id,
                'employee_id' => 'EMP003',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // End User
        User::updateOrCreate(
            ['email' => 'john.doe@company.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('user123'),
                'must_change_password' => true,
                'role_id' => Role::endUser()->id,
                'employee_id' => 'EMP004',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
    }
}
