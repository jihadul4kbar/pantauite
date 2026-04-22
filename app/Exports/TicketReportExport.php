<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TicketReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
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
        if (isset($this->reportData['tickets'])) {
            return collect($this->reportData['tickets']);
        }

        if (isset($this->reportData['staff'])) {
            return collect($this->reportData['staff']);
        }

        if (isset($this->reportData['sla_data'])) {
            return collect($this->reportData['sla_data']);
        }

        return collect();
    }

    public function headings(): array
    {
        if (isset($this->reportData['tickets'])) {
            return [
                'Ticket Number',
                'Subject',
                'Status',
                'Priority',
                'Category',
                'Department',
                'Assignee',
                'Created At',
                'Resolved At',
                'SLA Deadline',
                'SLA Breached',
            ];
        }

        if (isset($this->reportData['staff'])) {
            return [
                'Staff Name',
                'Total Tickets',
                'Resolved Tickets',
                'In Progress',
                'Avg Resolution Time (hours)',
                'SLA Breached',
            ];
        }

        return [
            'Metric',
            'Value',
        ];
    }

    public function map($row): array
    {
        if (isset($this->reportData['tickets'])) {
            return [
                $row['ticket_number'] ?? $row->ticket_number ?? '',
                $row['subject'] ?? $row->subject ?? '',
                ucfirst(str_replace('_', ' ', $row['status'] ?? $row->status ?? '')),
                ucfirst($row['priority'] ?? $row->priority ?? ''),
                $row['category'] ?? ($row->category?->name ?? ''),
                $row['department'] ?? ($row->department?->name ?? ''),
                $row['assignee'] ?? ($row->assignee?->name ?? 'Unassigned'),
                $row['created_at'] ?? ($row->created_at?->format('Y-m-d H:i') ?? ''),
                $row['resolved_at'] ?? ($row->resolved_at?->format('Y-m-d H:i') ?? ''),
                $row['sla_deadline'] ?? ($row->sla_deadline?->format('Y-m-d H:i') ?? ''),
                ($row['sla_breached'] ?? $row->sla_breached ?? false) ? 'Yes' : 'No',
            ];
        }

        if (isset($this->reportData['staff'])) {
            return [
                $row['name'] ?? $row->user?->name ?? '',
                $row['total_tickets'] ?? 0,
                $row['resolved_tickets'] ?? 0,
                $row['in_progress'] ?? 0,
                round($row['avg_resolution_hours'] ?? 0, 2),
                $row['sla_breached'] ?? 0,
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
        return 'Ticket Report';
    }
}
