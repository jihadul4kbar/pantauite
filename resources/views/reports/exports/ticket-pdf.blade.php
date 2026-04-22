<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #10B981; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #10B981; font-size: 18px; }
        .header p { margin: 5px 0 0; color: #666; font-size: 9px; }
        .meta { margin-bottom: 15px; font-size: 9px; color: #666; }
        .meta span { margin-right: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #10B981; color: white; padding: 8px 6px; text-align: left; font-size: 9px; }
        td { padding: 6px; border-bottom: 1px solid #e5e7eb; font-size: 9px; }
        tr:nth-child(even) { background-color: #f9fafb; }
        .footer { margin-top: 20px; text-align: center; font-size: 8px; color: #999; border-top: 1px solid #e5e7eb; padding-top: 10px; }
        .badge { padding: 2px 6px; border-radius: 3px; font-size: 8px; font-weight: bold; }
        .badge-green { background-color: #d1fae5; color: #065f46; }
        .badge-red { background-color: #fee2e2; color: #991b1b; }
        .badge-yellow { background-color: #fef3c7; color: #92400e; }
        .badge-blue { background-color: #dbeafe; color: #1e40af; }
        .summary { margin-bottom: 15px; }
        .summary table { margin-top: 5px; }
        .summary td { padding: 4px 6px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Ticket Report</h1>
        <p>Generated on {{ now()->format('d M Y, H:i') }} by {{ auth()->user()->name }}</p>
    </div>

    <div class="meta">
        <span><strong>Report Type:</strong> {{ ucfirst(str_replace('_', ' ', $filters['report_type'])) }}</span>
        @if(!empty($filters['date_from']))
        <span><strong>Date From:</strong> {{ $filters['date_from'] }}</span>
        @endif
        @if(!empty($filters['date_to']))
        <span><strong>Date To:</strong> {{ $filters['date_to'] }}</span>
        @endif
        @if(!empty($filters['status']))
        <span><strong>Status:</strong> {{ ucfirst($filters['status']) }}</span>
        @endif
        @if(!empty($filters['priority']))
        <span><strong>Priority:</strong> {{ ucfirst($filters['priority']) }}</span>
        @endif
    </div>

    @if(isset($reportData['summary']))
    <div class="summary">
        <h3>Summary</h3>
        <table>
            @foreach($reportData['summary'] as $key => $value)
            <tr>
                <td><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}</strong></td>
                <td>{{ is_array($value) ? json_encode($value) : $value }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    @if(isset($reportData['tickets']))
    <table>
        <thead>
            <tr>
                <th>Ticket #</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Category</th>
                <th>Assignee</th>
                <th>Created</th>
                <th>SLA</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData['tickets'] as $ticket)
            <tr>
                <td>{{ $ticket['ticket_number'] ?? $ticket->ticket_number ?? '' }}</td>
                <td>{{ Str::limit($ticket['subject'] ?? $ticket->subject ?? '', 40) }}</td>
                <td>
                    @php
                        $status = $ticket['status'] ?? $ticket->status ?? '';
                        $badgeClass = match($status) {
                            'open' => 'badge-blue',
                            'in_progress' => 'badge-yellow',
                            'resolved' => 'badge-green',
                            'closed' => 'badge-green',
                            default => 'badge-blue',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                </td>
                <td>{{ ucfirst($ticket['priority'] ?? $ticket->priority ?? '') }}</td>
                <td>{{ $ticket['category'] ?? ($ticket->category?->name ?? '') }}</td>
                <td>{{ $ticket['assignee'] ?? ($ticket->assignee?->name ?? 'Unassigned') }}</td>
                <td>{{ $ticket['created_at'] ?? ($ticket->created_at?->format('d/m/Y') ?? '') }}</td>
                <td>
                    @if($ticket['sla_breached'] ?? $ticket->sla_breached ?? false)
                    <span class="badge badge-red">Breached</span>
                    @else
                    <span class="badge badge-green">OK</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    @if(isset($reportData['staff']))
    <table>
        <thead>
            <tr>
                <th>Staff Name</th>
                <th>Total Tickets</th>
                <th>Resolved</th>
                <th>In Progress</th>
                <th>Avg Resolution (hrs)</th>
                <th>SLA Breached</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData['staff'] as $staff)
            <tr>
                <td>{{ $staff['name'] ?? $staff->user?->name ?? '' }}</td>
                <td>{{ $staff['total_tickets'] ?? 0 }}</td>
                <td>{{ $staff['resolved_tickets'] ?? 0 }}</td>
                <td>{{ $staff['in_progress'] ?? 0 }}</td>
                <td>{{ round($staff['avg_resolution_hours'] ?? 0, 2) }}</td>
                <td>{{ $staff['sla_breached'] ?? 0 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>PantauITE - IT Service Management Platform | Generation Time: {{ $generationTime }}ms</p>
    </div>
</body>
</html>
