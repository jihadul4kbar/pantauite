<?php

namespace Database\Seeders;

use App\Models\KbCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class KbCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Getting Started',
                'description' => 'Guides untuk new users dan basic setup',
                'icon' => '🚀',
                'sort_order' => 1,
            ],
            [
                'name' => 'Troubleshooting',
                'description' => 'Common issues dan solutions',
                'icon' => '🔧',
                'sort_order' => 2,
            ],
            [
                'name' => 'How-To Guides',
                'description' => 'Step-by-step guides untuk common tasks',
                'icon' => '📖',
                'sort_order' => 3,
            ],
            [
                'name' => 'SOP & Policies',
                'description' => 'Standard Operating Procedures dan IT policies',
                'icon' => '📜',
                'sort_order' => 4,
            ],
            [
                'name' => 'FAQ',
                'description' => 'Frequently Asked Questions',
                'icon' => '❓',
                'sort_order' => 5,
            ],
        ];

        foreach ($categories as $cat) {
            KbCategory::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'icon' => $cat['icon'],
                    'sort_order' => $cat['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
