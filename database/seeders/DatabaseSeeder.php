<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            DepartmentSeeder::class,
            SlaPolicySeeder::class,
            UserSeeder::class,
            TicketCategorySeeder::class,
            KbCategorySeeder::class,
            VendorSeeder::class,
            AssetSeeder::class,
            TicketSeeder::class,
        ]);
    }
}
