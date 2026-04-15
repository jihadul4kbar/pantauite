<?php

namespace Database\Seeders;

use App\Models\TicketCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TicketCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Hardware',
                'description' => 'Hardware-related issues (PC, laptop, server, printer, dll)',
                'icon' => '🖥️',
                'color' => '#3B82F6',
            ],
            [
                'name' => 'Software',
                'description' => 'Software-related issues (applications, OS, licenses)',
                'icon' => '💿',
                'color' => '#8B5CF6',
            ],
            [
                'name' => 'Network',
                'description' => 'Network-related issues (internet, connectivity, WiFi)',
                'icon' => '🌐',
                'color' => '#10B981',
            ],
            [
                'name' => 'Access & Security',
                'description' => 'User access, password, security issues',
                'icon' => '🔐',
                'color' => '#EF4444',
            ],
            [
                'name' => 'Email',
                'description' => 'Email-related issues (outlook, exchange, spam)',
                'icon' => '📧',
                'color' => '#F59E0B',
            ],
            [
                'name' => 'Request',
                'description' => 'General IT requests (new software, equipment, access)',
                'icon' => '📋',
                'color' => '#6366F1',
            ],
        ];

        foreach ($categories as $cat) {
            TicketCategory::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'icon' => $cat['icon'],
                    'color' => $cat['color'],
                    'is_active' => true,
                ]
            );
        }
    }
}
