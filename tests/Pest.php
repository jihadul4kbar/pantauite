<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function seedRoles()
{
    \App\Models\Role::create([
        'name' => 'super_admin',
        'display_name' => 'Super Admin',
        'description' => 'Full system access',
        'permissions' => ['*'],
        'is_system_role' => true,
    ]);
    
    \App\Models\Role::create([
        'name' => 'it_manager',
        'display_name' => 'IT Manager',
        'description' => 'Manage IT operations',
        'permissions' => ['manage-departments', 'manage-tickets', 'manage-assets', 'manage-kb', 'view-reports', 'view-all-tickets', 'create-tickets'],
        'is_system_role' => true,
    ]);
    
    \App\Models\Role::create([
        'name' => 'it_staff',
        'display_name' => 'IT Staff',
        'description' => 'Handle tickets and assets',
        'permissions' => ['view-tickets', 'update-tickets', 'manage-assets', 'manage-kb', 'view-reports', 'view-dashboard', 'create-tickets'],
        'is_system_role' => true,
    ]);
    
    \App\Models\Role::create([
        'name' => 'end_user',
        'display_name' => 'End User',
        'description' => 'Regular employee',
        'permissions' => ['create-tickets', 'view-own-tickets', 'view-kb'],
        'is_system_role' => true,
    ]);
}
