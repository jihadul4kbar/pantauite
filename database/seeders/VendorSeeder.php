<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = [
            [
                'name' => 'PT Dell Indonesia',
                'code' => 'DELL',
                'contact_person' => 'Budi Santoso',
                'email' => 'sales@dell.co.id',
                'phone' => '+62-21-5790-1234',
                'address' => 'Jl. Jend. Sudirman Kav. 52-53, Jakarta Selatan 12190',
                'website' => 'https://www.dell.com/id',
                'vendor_type' => 'hardware',
                'is_active' => true,
                'notes' => 'Primary hardware vendor untuk server dan workstation',
            ],
            [
                'name' => 'PT Cisco Systems Indonesia',
                'code' => 'CISCO',
                'contact_person' => 'Siti Rahayu',
                'email' => 'sales@cisco.co.id',
                'phone' => '+62-21-2992-1234',
                'address' => 'Sudirman Plaza, Jl. Jend. Sudirman Kav. 76-78, Jakarta Selatan 12190',
                'website' => 'https://www.cisco.com/id',
                'vendor_type' => 'network',
                'is_active' => true,
                'notes' => 'Network equipment vendor - switches, routers, APs',
            ],
            [
                'name' => 'PT Fortinet Indonesia',
                'code' => 'FORTINET',
                'contact_person' => 'Andi Wijaya',
                'email' => 'info@fortinet.co.id',
                'phone' => '+62-21-5088-1234',
                'address' => 'Menara Astra Lt. 45, Jl. Jend. Sudirman Kav. 5-6, Jakarta Pusat 10220',
                'website' => 'https://www.fortinet.com',
                'vendor_type' => 'network',
                'is_active' => true,
                'notes' => 'Firewall dan security equipment vendor',
            ],
            [
                'name' => 'PT HP Indonesia',
                'code' => 'HP',
                'contact_person' => 'Dewi Lestari',
                'email' => 'sales@hp.co.id',
                'phone' => '+62-21-2938-1234',
                'address' => 'Wisma GKBI Lt. 18, Jl. Jend. Sudirman No. 28, Jakarta Pusat 10210',
                'website' => 'https://www.hp.com/id',
                'vendor_type' => 'hardware',
                'is_active' => true,
                'notes' => 'Printers dan workstations',
            ],
            [
                'name' => 'PT Lenovo Indonesia',
                'code' => 'LENOVO',
                'contact_person' => 'Rudi Hermawan',
                'email' => 'sales@lenovo.co.id',
                'phone' => '+62-21-3972-1234',
                'address' => 'Menara Rajawali Lt. 20, Jl. DR. Ide Anak Agung Gde Agung, Jakarta Selatan 12950',
                'website' => 'https://www.lenovo.com/id',
                'vendor_type' => 'hardware',
                'is_active' => true,
                'notes' => 'Laptops, desktops, monitors',
            ],
            [
                'name' => 'PT Microsoft Indonesia',
                'code' => 'MSFT',
                'contact_person' => 'Linda Susanti',
                'email' => 'licensing@microsoft.co.id',
                'phone' => '+62-21-2927-1234',
                'address' => 'Pacific Century Place Lt. 8, SCBD Lot 10, Jakarta Selatan 12190',
                'website' => 'https://www.microsoft.com/id',
                'vendor_type' => 'software',
                'is_active' => true,
                'notes' => 'Software licensing - Windows, Office, Azure',
            ],
            [
                'name' => 'PT APC Schneider Electric',
                'code' => 'APC',
                'contact_person' => 'Hendra Gunawan',
                'email' => 'support@apc.com',
                'phone' => '+62-21-2858-1234',
                'address' => 'Menara Astra Lt. 32, Jl. Jend. Sudirman Kav. 5-6, Jakarta Pusat 10220',
                'website' => 'https://www.apc.com',
                'vendor_type' => 'hardware',
                'is_active' => true,
                'notes' => 'UPS dan power management solutions',
            ],
            [
                'name' => 'PT Veeam Partner Indonesia',
                'code' => 'VEEAM',
                'contact_person' => 'Agus Prasetyo',
                'email' => 'sales@veeam-partner.co.id',
                'phone' => '+62-21-5150-1234',
                'address' => 'Cyber 2 Tower Lt. 25, Jl. HR Rasuna Said Blok X-5 No. 13, Jakarta Selatan 12950',
                'website' => 'https://www.veeam.com',
                'vendor_type' => 'software',
                'is_active' => true,
                'notes' => 'Backup dan disaster recovery solutions',
            ],
            [
                'name' => 'PT Ubiquiti Networks Indonesia',
                'code' => 'UBNT',
                'contact_person' => 'Yoga Pratama',
                'email' => 'info@ubnt.co.id',
                'phone' => '+62-21-2905-1234',
                'address' => 'Equity Tower Lt. 15, SCBD, Jl. Jend. Sudirman Kav. 52-53, Jakarta Selatan 12190',
                'website' => 'https://www.ui.com',
                'vendor_type' => 'network',
                'is_active' => true,
                'notes' => 'WiFi dan networking equipment alternatif',
            ],
            [
                'name' => 'PT Epson Indonesia',
                'code' => 'EPSON',
                'contact_person' => 'Maya Sari',
                'email' => 'sales@epson.co.id',
                'phone' => '+62-21-6530-1234',
                'address' => 'Epson Tower, Jl. Raya Boulevard Timur, Jakarta Utara 14240',
                'website' => 'https://www.epson.co.id',
                'vendor_type' => 'hardware',
                'is_active' => true,
                'notes' => 'Printers dan projector',
            ],
        ];

        foreach ($vendors as $vendor) {
            Vendor::updateOrCreate(
                ['code' => $vendor['code']],
                $vendor
            );
            $this->command->info("Created/Updated vendor: {$vendor['name']}");
        }

        $this->command->info('Vendor seeding completed!');
    }
}
