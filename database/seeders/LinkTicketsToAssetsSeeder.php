<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class LinkTicketsToAssetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Map ticket numbers to asset codes
        $ticketAssetMap = [
            'TKT-2026-0001' => 'AST-HW-0001', // Dell Laptop
            'TKT-2026-0002' => 'AST-HW-0002', // HP Printer
            'TKT-2026-0003' => 'AST-HW-0003', // Dell Server
            'TKT-2026-0004' => 'AST-HW-0004', // Monitor
            'TKT-2026-0005' => 'AST-HW-0005', // UPS
            'TKT-2026-0006' => 'AST-SW-0002', // MS 365
            'TKT-2026-0007' => 'AST-NW-0001', // Cisco Switch
            'TKT-2026-0008' => 'AST-NW-0002', // FortiGate
            'TKT-2026-0009' => 'AST-SW-0003', // Veeam
            'TKT-2026-0010' => 'AST-NW-0003', // Cisco AP
        ];

        $updated = 0;

        foreach ($ticketAssetMap as $ticketNumber => $assetCode) {
            $ticket = Ticket::where('ticket_number', $ticketNumber)->first();
            $asset = Asset::where('asset_code', $assetCode)->first();

            if ($ticket && $asset) {
                $ticket->update(['asset_id' => $asset->id]);
                $updated++;
                $this->command->info("Linked {$ticketNumber} → {$assetCode}");
            } else {
                if (!$ticket) {
                    $this->command->error("Ticket {$ticketNumber} not found");
                }
                if (!$asset) {
                    $this->command->error("Asset {$assetCode} not found");
                }
            }
        }

        $this->command->info("Successfully linked {$updated} tickets to assets!");
    }
}
