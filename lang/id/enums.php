<?php

return [
    // Ticket Status
    'ticket_status' => [
        'open' => 'Terbuka',
        'in_progress' => 'Dikerjakan',
        'resolved' => 'Selesai',
        'closed' => 'Ditutup',
    ],

    // Ticket Priority
    'ticket_priority' => [
        'critical' => 'Kritis',
        'high' => 'Tinggi',
        'medium' => 'Sedang',
        'low' => 'Rendah',
    ],

    // Asset Status
    'asset_status' => [
        'procurement' => 'Pengadaan',
        'inventory' => 'Inventaris',
        'deployed' => 'Diterapkan',
        'maintenance' => 'Pemeliharaan',
        'retired' => 'Pensiun',
        'disposed' => 'Dibuang',
    ],

    // Asset Type
    'asset_type' => [
        'hardware' => 'Perangkat Keras',
        'software' => 'Perangkat Lunak',
        'network' => 'Jaringan',
    ],

    // Asset Condition
    'asset_condition' => [
        'new' => 'Baru',
        'good' => 'Baik',
        'fair' => 'Cukup',
        'poor' => 'Buruk',
        'damaged' => 'Rusak',
    ],

    // Maintenance Task Status
    'maintenance_task_status' => [
        'pending' => 'Menunggu',
        'scheduled' => 'Terjadwal',
        'in_progress' => 'Dikerjakan',
        'completed' => 'Selesai',
        'overdue' => 'Terlambat',
        'cancelled' => 'Dibatalkan',
    ],

    // Maintenance Task Priority
    'maintenance_task_priority' => [
        'critical' => 'Kritis',
        'high' => 'Tinggi',
        'medium' => 'Sedang',
        'low' => 'Rendah',
    ],

    // Maintenance Schedule Frequency
    'maintenance_frequency' => [
        'daily' => 'Harian',
        'weekly' => 'Mingguan',
        'monthly' => 'Bulanan',
        'quarterly' => 'Triwulanan',
        'semi_annual' => 'Semesteran',
        'annual' => 'Tahunan',
    ],

    // Department Status
    'department_status' => [
        'active' => 'Aktif',
        'inactive' => 'Tidak Aktif',
    ],

    // KB Article Status
    'kb_status' => [
        'draft' => 'Konsep',
        'published' => 'Diterbitkan',
        'archived' => 'Diarsipkan',
    ],

    // Inventory Part Status
    'inventory_status' => [
        'in_stock' => 'Tersedia',
        'low_stock' => 'Stok Rendah',
        'out_of_stock' => 'Stok Habis',
    ],
];
