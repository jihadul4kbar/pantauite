<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin - Full system access
        Role::updateOrCreate(
            ['name' => 'super_admin'],
            [
                'display_name' => 'Super Admin',
                'description' => 'Full system access dengan ability untuk manage users, roles, dan system configuration',
                'permissions' => ['*'],
                'is_system_role' => true,
            ]
        );

        // IT Manager - Manage IT operations
        Role::updateOrCreate(
            ['name' => 'it_manager'],
            [
                'display_name' => 'IT Manager',
                'description' => 'Manage IT operations, monitor team performance, configure SLA policies, dan generate reports',
                'permissions' => [
                    'manage-departments',
                    'manage-tickets',
                    'manage-assets',
                    'manage-kb',
                    'manage-sla',
                    'manage-categories',
                    'manage-vendors',
                    'manage-reports',
                    'view-all-tickets',
                    'view-reports',
                    'view-dashboard',
                    'view-audit-logs',
                    'view-kb',
                    'create-tickets',
                    'update-own-tickets',
                    'comment-tickets',
                    'assign-tickets',
                    'export-reports',
                ],
                'is_system_role' => true,
            ]
        );

        // IT Staff - Handle tickets dan assets
        Role::updateOrCreate(
            ['name' => 'it_staff'],
            [
                'display_name' => 'IT Staff',
                'description' => 'Handle tickets, manage assigned assets, create dan maintain KB articles',
                'permissions' => [
                    'manage-assets',
                    'view-assets',
                    'manage-kb',
                    'view-kb',
                    'view-own-tickets',
                    'create-tickets',
                    'update-own-tickets',
                    'comment-tickets',
                    'view-reports',
                    'view-dashboard',
                    'export-reports',
                ],
                'is_system_role' => true,
            ]
        );

        // End User - Regular employees
        Role::updateOrCreate(
            ['name' => 'end_user'],
            [
                'display_name' => 'End User',
                'description' => 'Regular employees yang menggunakan sistem untuk submit tickets dan access knowledge base',
                'permissions' => [
                    'view-kb',
                    'create-tickets',
                    'view-own-tickets',
                    'update-own-tickets',
                    'comment-tickets',
                ],
                'is_system_role' => true,
            ]
        );
    }
}
