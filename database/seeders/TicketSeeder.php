<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Department;
use App\Models\Ticket;
use App\Models\TicketAuditLog;
use App\Models\TicketCategory;
use App\Models\TicketComment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = Department::all();
        $categories = TicketCategory::all();
        $users = User::where('role_id', '!=', 1)->get(); // Exclude super admin
        $itStaff = User::whereIn('role_id', function($query) {
            $query->select('id')->from('roles')->whereIn('name', ['it_staff', 'it_manager']);
        })->get();

        if ($users->isEmpty() || $itStaff->isEmpty()) {
            $this->command->error('Please seed users and departments first!');
            return;
        }

        $tickets = $this->getDummyTickets();

        foreach ($tickets as $ticketData) {
            $ticket = Ticket::updateOrCreate(
                ['ticket_number' => $ticketData['ticket']['ticket_number']],
                $ticketData['ticket']
            );

            // Add audit log
            TicketAuditLog::create([
                'ticket_id' => $ticket->id,
                'user_id' => $ticket->user_id,
                'action' => 'created',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Seeder',
            ]);

            // Add comments if any
            if (isset($ticketData['comments'])) {
                foreach ($ticketData['comments'] as $comment) {
                    TicketComment::create(array_merge($comment, [
                        'ticket_id' => $ticket->id,
                    ]));
                }
            }

            $relationType = isset($ticketData['ticket']['asset_id']) ? 'with Asset' : 'without Asset';
            $this->command->info("Created ticket: {$ticket->ticket_number} - {$relationType}");
        }

        $this->command->info('Ticket seeding completed!');
    }

    /**
     * Get dummy ticket data
     */
    protected function getDummyTickets(): array
    {
        $users = User::where('role_id', '!=', 1)->get();
        $itStaff = User::whereIn('role_id', function($query) {
            $query->select('id')->from('roles')->whereIn('name', ['it_staff', 'it_manager']);
        })->get();
        $departments = Department::all();
        $categories = TicketCategory::all();

        // Get assets for reference
        $laptops = Asset::where('asset_type', 'hardware')->where('name', 'like', '%Laptop%')->get();
        $printers = Asset::where('asset_type', 'hardware')->where('name', 'like', '%Printer%')->get();
        $servers = Asset::where('asset_type', 'hardware')->where('name', 'like', '%Server%')->get();
        $monitors = Asset::where('asset_type', 'hardware')->where('name', 'like', '%Monitor%')->get();
        $software = Asset::where('asset_type', 'software')->get();
        $network = Asset::where('asset_type', 'network')->get();

        return [
            // ==========================================
            // TICKETS RELATED TO ASSETS
            // ==========================================
            
            // 1. Laptop issue - with asset reference
            [
                'ticket' => [
                    'ticket_number' => 'TKT-2026-0001',
                    'subject' => 'Laptop Dell Latitude 5540 (AST-HW-0001) tidak bisa booting',
                    'description' => "Laptop Dell Latitude 5540 dengan asset code AST-HW-0001 tidak bisa booting sejak pagi. Sudah dicoba restart beberapa kali tapi tetap tidak berhasil.\n\n**Asset Details:**\n- Asset Code: AST-HW-0001\n- Asset Name: Dell Latitude 5540 Laptop\n- Serial: SN-DELL-2024-001\n\nMohon segera ditindaklanjuti karena dibutuhkan untuk kerja.",
                    'status' => 'open',
                    'priority' => 'high',
                    'user_id' => $users->first()?->id ?? 4,
                    'assignee_id' => $itStaff->first()?->id ?? 3,
                    'department_id' => $departments->first()?->id ?? 1,
                    'category_id' => $categories->where('name', 'like', '%Hardware%')->first()?->id ?? 1,
                    'asset_id' => Asset::where('asset_code', 'AST-HW-0001')->first()?->id,
                    'sla_policy_id' => 2,
                    'sla_deadline' => now()->addHours(8),
                    'source' => 'web',
                    'created_at' => now()->subHours(2),
                ],
                'comments' => [
                    [
                        'user_id' => $itStaff->first()?->id ?? 3,
                        'comment' => 'Ticket diterima, akan segera dicek.',
                        'is_internal' => false,
                        'created_at' => now()->subHours(1),
                    ],
                ],
            ],

            // 2. Printer issue - with asset reference
            [
                'ticket' => [
                    'ticket_number' => 'TKT-2026-0002',
                    'subject' => 'Printer HP LaserJet (AST-HW-0002) error: Paper Jam',
                    'description' => "Printer HP LaserJet Pro M404dn dengan asset code AST-HW-0002 mengalami error Paper Jam terus menerus.\n\n**Asset Details:**\n- Asset Code: AST-HW-0002\n- Asset Name: HP LaserJet Pro M404dn Printer\n- Location: Building A, Floor 2, Room 201\n\nSudah dicoba bersihkan paper path tapi masih error. Mohon bantuan teknisi.",
                    'status' => 'in_progress',
                    'priority' => 'medium',
                    'user_id' => $users->first()?->id ?? 4,
                    'assignee_id' => $itStaff->first()?->id ?? 3,
                    'department_id' => $departments->first()?->id ?? 1,
                    'category_id' => $categories->where('name', 'like', '%Hardware%')->first()?->id ?? 1,
                    'sla_policy_id' => 3,
                    'sla_deadline' => now()->addHours(24),
                    'source' => 'web',
                    'created_at' => now()->subHours(5),
                ],
                'comments' => [
                    [
                        'user_id' => $itStaff->first()?->id ?? 3,
                        'comment' => 'Sudah cek printer, rollers printer perlu diganti. Saya order part replacement.',
                        'is_internal' => true,
                        'created_at' => now()->subHours(4),
                    ],
                    [
                        'user_id' => $itStaff->first()?->id ?? 3,
                        'comment' => 'Sedang menunggu part replacement ETA 2 hari.',
                        'is_internal' => false,
                        'created_at' => now()->subHours(3),
                    ],
                ],
            ],

            // 3. Server high CPU - with asset reference
            [
                'ticket' => [
                    'ticket_number' => 'TKT-2026-0003',
                    'subject' => '[CRITICAL] Server Dell R750 (AST-HW-0003) CPU Usage 95%',
                    'description' => "Server Dell PowerEdge R750 dengan asset code AST-HW-0003 menunjukkan CPU usage 95% sejak 1 jam yang lalu.\n\n**Asset Details:**\n- Asset Code: AST-HW-0003\n- Asset Name: Dell PowerEdge R750 Server\n- Location: Data Center, Rack 5, U10-U12\n\nMultiple services affected. Need immediate attention!",
                    'status' => 'resolved',
                    'priority' => 'critical',
                    'user_id' => $itStaff->last()?->id ?? 3,
                    'assignee_id' => $itStaff->first()?->id ?? 3,
                    'department_id' => $departments->first()?->id ?? 1,
                    'category_id' => $categories->where('name', 'like', '%Server%')->first()?->id ?? 1,
                    'sla_policy_id' => 1,
                    'sla_deadline' => now()->subHours(2),
                    'resolved_at' => now()->subHour(),
                    'first_response_at' => now()->subHours(3),
                    'source' => 'web',
                    'created_at' => now()->subHours(4),
                ],
                'comments' => [
                    [
                        'user_id' => $itStaff->first()?->id ?? 3,
                        'comment' => 'Investigating... Found run-away process. Killing process and monitoring.',
                        'is_internal' => false,
                        'created_at' => now()->subHours(3),
                    ],
                    [
                        'user_id' => $itStaff->first()?->id ?? 3,
                        'comment' => 'Issue resolved. It was a backup process stuck in loop. Restarted backup service and confirmed CPU back to normal (15%).',
                        'is_internal' => false,
                        'is_solution' => true,
                        'created_at' => now()->subHour(),
                    ],
                ],
            ],

            // 4. Monitor replacement - with asset reference
            [
                'ticket' => [
                    'ticket_number' => 'TKT-2026-0004',
                    'subject' => 'Request replacement monitor ThinkVision (AST-HW-0004) - dead pixel',
                    'description' => "Monitor ThinkVision P27h-20 dengan asset code AST-HW-0004 mengalami dead pixel di tengah layar.\n\n**Asset Details:**\n- Asset Code: AST-HW-0004\n- Asset Name: ThinkVision P27h-20 Monitor\n- Condition: New\n- Status: Inventory\n\nKarena masih dalam warranty dan kondisi masih baru, mohon replacement.",
                    'status' => 'open',
                    'priority' => 'low',
                    'user_id' => $itStaff->first()?->id ?? 3,
                    'assignee_id' => null,
                    'department_id' => $departments->first()?->id ?? 1,
                    'category_id' => $categories->where('name', 'like', '%Hardware%')->first()?->id ?? 1,
                    'sla_policy_id' => 4,
                    'sla_deadline' => now()->addHours(72),
                    'source' => 'web',
                    'created_at' => now()->subHours(1),
                ],
            ],

            // 5. UPS maintenance - with asset reference
            [
                'ticket' => [
                    'ticket_number' => 'TKT-2026-0005',
                    'subject' => 'UPS APC Smart-UPS (AST-HW-0005) perlu maintenance rutin',
                    'description' => "UPS APC Smart-UPS 3000VA dengan asset code AST-HW-0005 sudah 6 bulan tidak maintenance.\n\n**Asset Details:**\n- Asset Code: AST-HW-0005\n- Asset Name: APC Smart-UPS 3000VA\n- Location: Data Center, Rack 1\n- Last maintenance: 6 months ago\n\nJadwalkan preventive maintenance: battery health check, firmware update, load test.",
                    'status' => 'in_progress',
                    'priority' => 'medium',
                    'user_id' => $itStaff->first()?->id ?? 3,
                    'assignee_id' => $itStaff->first()?->id ?? 3,
                    'department_id' => $departments->first()?->id ?? 1,
                    'category_id' => $categories->where('name', 'like', '%Maintenance%')->first()?->id ?? 1,
                    'sla_policy_id' => 3,
                    'sla_deadline' => now()->addHours(24),
                    'source' => 'web',
                    'created_at' => now()->subDays(2),
                ],
                'comments' => [
                    [
                        'user_id' => $itStaff->first()?->id ?? 3,
                        'comment' => 'Scheduled for next week Tuesday. Vendor APC sudah dikonfirmasi.',
                        'is_internal' => false,
                        'created_at' => now()->subDay(),
                    ],
                ],
            ],

            // 6. Software license issue - with asset reference
            [
                'ticket' => [
                    'ticket_number' => 'TKT-2026-0006',
                    'subject' => 'Microsoft 365 (AST-SW-0002) - User tidak bisa login Outlook',
                    'description' => "Beberapa user melaporkan tidak bisa login ke Outlook.\n\n**Asset Details:**\n- Asset Code: AST-SW-0002\n- Asset Name: Microsoft 365 Business Premium Licenses\n- Seats: 50 users\n\nError message: \"Account does not exist\". Cek di admin center, semua akun masih aktif.",
                    'status' => 'open',
                    'priority' => 'high',
                    'user_id' => $users->first()?->id ?? 4,
                    'assignee_id' => null,
                    'department_id' => $departments->first()?->id ?? 1,
                    'category_id' => $categories->where('name', 'like', '%Software%')->first()?->id ?? 1,
                    'sla_policy_id' => 2,
                    'sla_deadline' => now()->addHours(6),
                    'source' => 'phone',
                    'created_at' => now()->subMinutes(30),
                ],
            ],

            // 7. Network switch issue - with asset reference
            [
                'ticket' => [
                    'ticket_number' => 'TKT-2026-0007',
                    'subject' => 'Switch Cisco C9200L (AST-NW-0001) port 15-20 down',
                    'description' => "Switch Cisco Catalyst 9200L dengan asset code AST-NW-0001, port 15-20 tidak aktif.\n\n**Asset Details:**\n- Asset Code: AST-NW-0001\n- Asset Name: Cisco Catalyst 9200L Switch\n- Location: Building A, MDF Room, Floor 1\n\nPort 15-20 showing 'err-disabled'. Beberapa user di Floor 3 tidak bisa connect network.",
                    'status' => 'closed',
                    'priority' => 'high',
                    'user_id' => $itStaff->first()?->id ?? 3,
                    'assignee_id' => $itStaff->first()?->id ?? 3,
                    'department_id' => $departments->first()?->id ?? 1,
                    'category_id' => $categories->where('name', 'like', '%Network%')->first()?->id ?? 1,
                    'sla_policy_id' => 2,
                    'sla_deadline' => now()->subDays(5),
                    'resolved_at' => now()->subDays(6),
                    'closed_at' => now()->subDays(5),
                    'first_response_at' => now()->subDays(7),
                    'source' => 'web',
                    'created_at' => now()->subDays(8),
                ],
                'comments' => [
                    [
                        'user_id' => $itStaff->first()?->id ?? 3,
                        'comment' => 'Root cause: loop detected on port 15. Disabled err-disable recovery. Enabled BPDU guard on all edge ports.',
                        'is_internal' => false,
                        'is_solution' => true,
                        'created_at' => now()->subDays(7),
                    ],
                ],
            ],

            // 8. Firewall firmware update - with asset reference
            [
                'ticket' => [
                    'ticket_number' => 'TKT-2026-0008',
                    'subject' => 'FortiGate 200F (AST-NW-0002) - Firmware upgrade to 7.4.3',
                    'description' => "Firewall FortiGate 200F dengan asset code AST-NW-0002 perlu upgrade firmware.\n\n**Asset Details:**\n- Asset Code: AST-NW-0002\n- Asset Name: FortiGate 200F Firewall\n- Current Version: FortiOS 7.4.2\n- Target Version: FortiOS 7.4.3\n\nScheduled maintenance window: Saturday 22:00-02:00",
                    'status' => 'in_progress',
                    'priority' => 'medium',
                    'user_id' => $itStaff->first()?->id ?? 3,
                    'assignee_id' => $itStaff->first()?->id ?? 3,
                    'department_id' => $departments->first()?->id ?? 1,
                    'category_id' => $categories->where('name', 'like', '%Network%')->first()?->id ?? 1,
                    'sla_policy_id' => 3,
                    'sla_deadline' => now()->addHours(24),
                    'source' => 'web',
                    'created_at' => now()->subDays(1),
                ],
            ],

            // 9. Backup software issue - with asset reference
            [
                'ticket' => [
                    'ticket_number' => 'TKT-2026-0009',
                    'subject' => 'Veeam Backup (AST-SW-0003) - Backup job failed',
                    'description' => "Veeam Backup & Replication dengan asset code AST-SW-0003, backup job gagal sejak 2 hari.\n\n**Asset Details:**\n- Asset Code: AST-SW-0003\n- Asset Name: Veeam Backup & Replication v12\n- Instances: 10\n\nError: \"Failed to create snapshot. VSS writer error.\"\nMultiple VMs affected.",
                    'status' => 'open',
                    'priority' => 'critical',
                    'user_id' => $itStaff->first()?->id ?? 3,
                    'assignee_id' => $itStaff->first()?->id ?? 3,
                    'department_id' => $departments->first()?->id ?? 1,
                    'category_id' => $categories->where('name', 'like', '%Software%')->first()?->id ?? 1,
                    'sla_policy_id' => 1,
                    'sla_deadline' => now()->addHours(2),
                    'source' => 'web',
                    'created_at' => now()->subHours(3),
                ],
            ],

            // 10. WiFi AP issue - with asset reference
            [
                'ticket' => [
                    'ticket_number' => 'TKT-2026-0010',
                    'subject' => 'Access Point Cisco (AST-NW-0003) - Clients sering disconnect',
                    'description' => "Access Point Cisco Catalyst 9120AXI dengan asset code AST-NW-0003, clients sering disconnect.\n\n**Asset Details:**\n- Asset Code: AST-NW-0003\n- Asset Name: Cisco Catalyst 9120AXI Access Point\n- Location: Building A, Floor 2, Ceiling\n\nKoneksi unstable, terutama saat peak hours. Sudah reboot AP tapi masalah masih ada.",
                    'status' => 'in_progress',
                    'priority' => 'high',
                    'user_id' => $users->first()?->id ?? 4,
                    'assignee_id' => $itStaff->first()?->id ?? 3,
                    'department_id' => $departments->first()?->id ?? 1,
                    'category_id' => $categories->where('name', 'like', '%Network%')->first()?->id ?? 1,
                    'sla_policy_id' => 2,
                    'sla_deadline' => now()->addHours(4),
                    'source' => 'walk-in',
                    'created_at' => now()->subHours(4),
                ],
                'comments' => [
                    [
                        'user_id' => $itStaff->first()?->id ?? 3,
                        'comment' => 'Checking RF environment for interference. Might need to adjust channel/power settings.',
                        'is_internal' => false,
                        'created_at' => now()->subHours(2),
                    ],
                ],
            ],

            // ==========================================
            // TICKETS NOT RELATED TO ASSETS
            // ==========================================

            // 11. Email setup request - no asset
            [
                'ticket' => [
                    'ticket_number' => 'TKT-2026-0011',
                    'subject' => 'Request setup email baru untuk karyawan',
                    'description' => "Mohon dibantu setup email untuk karyawan baru:\n\n**Details:**\n- Nama: Ahmad Fauzi\n- Department: Marketing\n- Start Date: 15 April 2026\n\nMohon dibuatkan email dengan format: ahmad.fauzi@company.com",
                    'status' => 'open',
                    'priority' => 'medium',
                    'user_id' => $users->skip(1)->first()?->id ?? 4,
                    'assignee_id' => null,
                    'department_id' => $departments->skip(1)->first()?->id ?? 2,
                    'category_id' => $categories->where('name', 'like', '%Email%')->first()?->id ?? 1,
                    'sla_policy_id' => 3,
                    'sla_deadline' => now()->addHours(24),
                    'source' => 'web',
                    'created_at' => now()->subHours(3),
                ],
            ],

            // 12. Password reset - no asset
            [
                'ticket' => [
                    'ticket_number' => 'TKT-2026-0012',
                    'subject' => 'Lupa password - Request reset password',
                    'description' => "Saya lupa password dan sudah mencoba fitur forgot password tapi email reset tidak masuk (sudah cek spam juga).\n\nMohon bantuan untuk reset password manual.\n\nTerima kasih.",
                    'status' => 'resolved',
                    'priority' => 'low',
                    'user_id' => $users->first()?->id ?? 4,
                    'assignee_id' => $itStaff->first()?->id ?? 3,
                    'department_id' => $departments->first()?->id ?? 1,
                    'category_id' => $categories->where('name', 'like', '%Password%')->first()?->id ?? 1,
                    'sla_policy_id' => 4,
                    'sla_deadline' => now()->subHours(10),
                    'resolved_at' => now()->subHours(11),
                    'first_response_at' => now()->subHours(12),
                    'source' => 'phone',
                    'created_at' => now()->subHours(13),
                ],
                'comments' => [
                    [
                        'user_id' => $itStaff->first()?->id ?? 3,
                        'comment' => 'Password sudah direset. Temporary password: Temp@2026! Silakan login dan ganti password.',
                        'is_internal' => false,
                        'is_solution' => true,
                        'created_at' => now()->subHours(12),
                    ],
                ],
            ],

            // 13. Software installation - no asset (generic request)
            [
                'ticket' => [
                    'ticket_number' => 'TKT-2026-0013',
                    'subject' => 'Request installasi Adobe Creative Suite untuk tim Design',
                    'description' => "Tim Design membutuhkan Adobe Creative Suite untuk pekerjaan sehari-hari.\n\n**Request Details:**\n- Software: Adobe Creative Suite (Photoshop, Illustrator, InDesign)\n- Jumlah: 5 users\n- Department: Marketing/Design\n\nMohon info availability license dan timeline instalasi.",
                    'status' => 'open',
                    'priority' => 'medium',
                    'user_id' => $users->skip(2)->first()?->id ?? 4,
                    'assignee_id' => null,
                    'department_id' => $departments->skip(1)->first()?->id ?? 2,
                    'category_id' => $categories->where('name', 'like', '%Software%')->first()?->id ?? 1,
                    'sla_policy_id' => 3,
                    'sla_deadline' => now()->addHours(24),
                    'source' => 'web',
                    'created_at' => now()->subHours(6),
                ],
            ],

            // 14. Network slow - no specific asset
            [
                'ticket' => [
                    'ticket_number' => 'TKT-2026-0014',
                    'subject' => 'Internet lambat di lantai 3',
                    'description' => "Koneksi internet sangat lambat di lantai 3 sejak pagi.\n\n**Issue Details:**\n- Location: Building A, Floor 3\n- Affected Users: ~20 users\n- Speed Test: 2 Mbps (normal: 50 Mbps)\n\nSudah coba restart router di lantai 3 tapi tidak membantu.",
                    'status' => 'in_progress',
                    'priority' => 'high',
                    'user_id' => $users->first()?->id ?? 4,
                    'assignee_id' => $itStaff->first()?->id ?? 3,
                    'department_id' => $departments->first()?->id ?? 1,
                    'category_id' => $categories->where('name', 'like', '%Network%')->first()?->id ?? 1,
                    'sla_policy_id' => 2,
                    'sla_deadline' => now()->addHours(6),
                    'source' => 'web',
                    'created_at' => now()->subHours(3),
                ],
                'comments' => [
                    [
                        'user_id' => $itStaff->first()?->id ?? 3,
                        'comment' => 'Investigating... Checking bandwidth usage and possible bottlenecks.',
                        'is_internal' => false,
                        'created_at' => now()->subHours(2),
                    ],
                ],
            ],

            // 15. Access request - no asset
            [
                'ticket' => [
                    'ticket_number' => 'TKT-2026-0015',
                    'subject' => 'Request akses ke shared folder Finance',
                    'description' => "Mohon dibantu untuk diberikan akses ke shared folder Finance.\n\n**Request Details:**\n- User: Sarah (sarah@company.com)\n- Department: Finance\n- Folder: \\\\fileserver\\Finance\\MonthlyReports\n- Access Level: Read/Write\n\nApproval dari Manager Finance sudah didapat (email terlampir).",
                    'status' => 'closed',
                    'priority' => 'low',
                    'user_id' => $users->first()?->id ?? 4,
                    'assignee_id' => $itStaff->first()?->id ?? 3,
                    'department_id' => $departments->first()?->id ?? 1,
                    'category_id' => $categories->where('name', 'like', '%Access%')->first()?->id ?? 1,
                    'sla_policy_id' => 4,
                    'sla_deadline' => now()->subDays(3),
                    'resolved_at' => now()->subDays(4),
                    'closed_at' => now()->subDays(3),
                    'first_response_at' => now()->subDays(5),
                    'source' => 'web',
                    'created_at' => now()->subDays(6),
                ],
                'comments' => [
                    [
                        'user_id' => $itStaff->first()?->id ?? 3,
                        'comment' => 'Access sudah diberikan. User bisa akses folder sekarang.',
                        'is_internal' => false,
                        'is_solution' => true,
                        'created_at' => now()->subDays(5),
                    ],
                ],
            ],

            // 16. New employee onboarding - no asset
            [
                'ticket' => [
                    'ticket_number' => 'TKT-2026-0016',
                    'subject' => 'Onboarding karyawan baru - Setup workstation & access',
                    'description' => "Ada 2 karyawan baru yang mulai Senin depan:\n\n**Employee 1:**\n- Nama: Rina Susanti\n- Position: Marketing Specialist\n- Department: Marketing\n- Start Date: 21 April 2026\n\n**Employee 2:**\n- Nama: Budi Setiawan\n- Position: Junior Developer\n- Department: IT\n- Start Date: 21 April 2026\n\nNeeds: Email, network access, shared folder access, software licenses",
                    'status' => 'in_progress',
                    'priority' => 'high',
                    'user_id' => $users->skip(1)->first()?->id ?? 4,
                    'assignee_id' => $itStaff->first()?->id ?? 3,
                    'department_id' => $departments->first()?->id ?? 1,
                    'category_id' => $categories->where('name', 'like', '%Onboarding%')->first()?->id ?? 1,
                    'sla_policy_id' => 2,
                    'sla_deadline' => now()->addHours(12),
                    'source' => 'web',
                    'created_at' => now()->subHours(8),
                ],
                'comments' => [
                    [
                        'user_id' => $itStaff->first()?->id ?? 3,
                        'comment' => 'Email accounts sudah dibuat. Workstation preparation in progress.',
                        'is_internal' => false,
                        'created_at' => now()->subHours(6),
                    ],
                ],
            ],

            // 17. Data recovery request - no asset
            [
                'ticket' => [
                    'ticket_number' => 'TKT-2026-0017',
                    'subject' => 'Request recovery file yang terhapus di server',
                    'description' => "File penting di server terhapus kemarin.\n\n**Details:**\n- Folder: \\\\fileserver\\Projects\\ProjectAlpha\n- Deleted: Yesterday around 16:00\n- Files: ~50 documents (DOCX, XLSX, PDF)\n\nMohon bisa di-restore dari backup terakhir.",
                    'status' => 'open',
                    'priority' => 'high',
                    'user_id' => $users->first()?->id ?? 4,
                    'assignee_id' => $itStaff->first()?->id ?? 3,
                    'department_id' => $departments->first()?->id ?? 1,
                    'category_id' => $categories->where('name', 'like', '%Backup%')->first()?->id ?? 1,
                    'sla_policy_id' => 2,
                    'sla_deadline' => now()->addHours(6),
                    'source' => 'phone',
                    'created_at' => now()->subHours(2),
                ],
            ],

            // 18. Training request - no asset
            [
                'ticket' => [
                    'ticket_number' => 'TKT-2026-0018',
                    'subject' => 'Request training cybersecurity awareness untuk semua staff',
                    'description' => "Mengingat meningkatnya ancaman cyber, kami ingin mengadakan training cybersecurity awareness untuk semua staff.\n\n**Request:**\n- Topic: Cybersecurity Awareness\n- Audience: All employees (~200 users)\n- Duration: 2 hours\n- Format: In-person atau virtual\n\nMohon rekomendasi trainer dan jadwal.",
                    'status' => 'open',
                    'priority' => 'low',
                    'user_id' => $itStaff->last()?->id ?? 3,
                    'assignee_id' => null,
                    'department_id' => $departments->first()?->id ?? 1,
                    'category_id' => $categories->where('name', 'like', '%Training%')->first()?->id ?? 1,
                    'sla_policy_id' => 4,
                    'sla_deadline' => now()->addDays(3),
                    'source' => 'web',
                    'created_at' => now()->subDays(1),
                ],
            ],

            // 19. Website issue - no asset
            [
                'ticket' => [
                    'ticket_number' => 'TKT-2026-0019',
                    'subject' => 'Website company down - Error 503',
                    'description' => "Website company (www.company.com) down sejak 30 menit yang lalu.\n\n**Error:** 503 Service Unavailable\n\nCek dari monitoring: Server response timeout. Perlu immediate attention!",
                    'status' => 'resolved',
                    'priority' => 'critical',
                    'user_id' => $itStaff->first()?->id ?? 3,
                    'assignee_id' => $itStaff->first()?->id ?? 3,
                    'department_id' => $departments->first()?->id ?? 1,
                    'category_id' => $categories->where('name', 'like', '%Server%')->first()?->id ?? 1,
                    'sla_policy_id' => 1,
                    'sla_deadline' => now()->subHours(2),
                    'resolved_at' => now()->subHour(),
                    'first_response_at' => now()->subHours(3),
                    'source' => 'web',
                    'created_at' => now()->subHours(4),
                ],
                'comments' => [
                    [
                        'user_id' => $itStaff->first()?->id ?? 3,
                        'comment' => 'Root cause: Database connection pool exhausted. Restarted database service and increased connection pool limit.',
                        'is_internal' => false,
                        'is_solution' => true,
                        'created_at' => now()->subHours(2),
                    ],
                ],
            ],

            // 20. General inquiry - no asset
            [
                'ticket' => [
                    'ticket_number' => 'TKT-2026-0020',
                    'subject' => 'Tanya prosedur WFH (Work From Home) access',
                    'description' => "Saya ingin mengetahui prosedur untuk akses remote/WFH.\n\n**Questions:**\n1. Bagaimana cara request VPN access?\n2. Apakah perlu approval dari manager?\n3. Software apa saja yang bisa diakses remote?\n4. Apakah ada batasan bandwidth?\n\nTerima kasih.",
                    'status' => 'closed',
                    'priority' => 'low',
                    'user_id' => $users->first()?->id ?? 4,
                    'assignee_id' => $itStaff->first()?->id ?? 3,
                    'department_id' => $departments->first()?->id ?? 1,
                    'category_id' => $categories->where('name', 'like', '%General%')->first()?->id ?? 1,
                    'sla_policy_id' => 4,
                    'sla_deadline' => now()->subDays(2),
                    'resolved_at' => now()->subDays(3),
                    'closed_at' => now()->subDays(2),
                    'first_response_at' => now()->subDays(4),
                    'source' => 'web',
                    'created_at' => now()->subDays(5),
                ],
                'comments' => [
                    [
                        'user_id' => $itStaff->first()?->id ?? 3,
                        'comment' => "Procedure WFH:\n1. Submit request melalui form ini dengan approval dari manager\n2. VPN access akan di-setup oleh IT (1-2 hari kerja)\n3. Software yang bisa diakses: Email, Shared Folders, Internal Apps\n4. Bandwidth: Unlimited untuk kerja, ada fair usage policy\n\nSilakan submit ticket baru dengan approval dari manager.",
                        'is_internal' => false,
                        'is_solution' => true,
                        'created_at' => now()->subDays(4),
                    ],
                ],
            ],
        ];
    }
}
