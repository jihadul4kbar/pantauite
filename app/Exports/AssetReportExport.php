<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssetReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $reportData;
    protected $filters;

    public function __construct(array $reportData, array $filters)
    {
        $this->reportData = $reportData;
        $this->filters = $filters;
    }

    public function collection()
    {
        if (isset($this->reportData['assets'])) {
            return collect($this->reportData['assets']);
        }

        if (isset($this->reportData['summary'])) {
            return collect($this->reportData['summary']);
        }

        return collect();
    }

    public function headings(): array
    {
        if (isset($this->reportData['assets'])) {
            return [
                'Asset Code',
                'Name',
                'Type',
                'Brand',
                'Model',
                'Serial Number',
                'Status',
                'Condition',
                'Assigned To',
                'Department',
                'Location',
                'Vendor',
                'Purchase Date',
                'Price',
                'Warranty End',
                'Depreciated Value',
            ];
        }

        return [
            'Metric',
            'Value',
        ];
    }

    public function map($row): array
    {
        if (isset($this->reportData['assets'])) {
            return [
                $row['asset_code'] ?? $row->asset_code ?? '',
                $row['name'] ?? $row->name ?? '',
                ucfirst($row['asset_type'] ?? $row->asset_type ?? ''),
                $row['brand'] ?? $row->brand ?? '',
                $row['model'] ?? $row->model ?? '',
                $row['serial_number'] ?? $row->serial_number ?? '',
                ucfirst(str_replace('_', ' ', $row['status'] ?? $row->status ?? '')),
                ucfirst($row['condition'] ?? $row->condition ?? ''),
                $row['assigned_to'] ?? ($row->assignedToUser?->name ?? ''),
                $row['department'] ?? ($row->department?->name ?? ''),
                $row['location'] ?? $row->location ?? '',
                $row['vendor'] ?? ($row->vendor?->name ?? ''),
                $row['purchase_date'] ?? ($row->purchase_date?->format('Y-m-d') ?? ''),
                $row['price'] ?? $row->price ?? '',
                $row['warranty_end'] ?? ($row->warranty_end?->format('Y-m-d') ?? ''),
                $row['depreciated_value'] ?? $row->depreciated_value ?? '',
            ];
        }

        return [
            $row['metric'] ?? $row['label'] ?? '',
            $row['value'] ?? $row['count'] ?? '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '10B981'],
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Asset Report';
    }
}
