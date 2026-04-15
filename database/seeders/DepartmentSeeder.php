<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Information Technology',
                'code' => 'IT',
                'description' => 'IT Department - Responsible for all IT infrastructure and support',
            ],
            [
                'name' => 'Human Resources',
                'code' => 'HR',
                'description' => 'HR Department - Manages employee relations and administration',
            ],
            [
                'name' => 'Finance',
                'code' => 'FIN',
                'description' => 'Finance Department - Handles financial operations and accounting',
            ],
            [
                'name' => 'Operations',
                'code' => 'OPS',
                'description' => 'Operations Department - Manages daily business operations',
            ],
            [
                'name' => 'Marketing',
                'code' => 'MKT',
                'description' => 'Marketing Department - Handles marketing activities and communications',
            ],
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(
                ['code' => $dept['code']],
                $dept
            );
        }
    }
}
