<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Asset Report</title>
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
        .currency { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Asset Report</h1>
        <p>Generated on {{ now()->format('d M Y, H:i') }} by {{ auth()->user()->name }}</p>
    </div>

    <div class="meta">
        <span><strong>Report Type:</strong> {{ ucfirst(str_replace('_', ' ', $filters['report_type'])) }}</span>
        @if(!empty($filters['asset_type']))
        <span><strong>Type:</strong> {{ ucfirst($filters['asset_type']) }}</span>
        @endif
        @if(!empty($filters['status']))
        <span><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $filters['status'])) }}</span>
        @endif
        @if(!empty($filters['vendor_id']))
        <span><strong>Vendor ID:</strong> {{ $filters['vendor_id'] }}</span>
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

    @if(isset($reportData['assets']))
    <table>
        <thead>
            <tr>
                <th>Asset Code</th>
                <th>Name</th>
                <th>Type</th>
                <th>Brand</th>
                <th>Status</th>
                <th>Condition</th>
                <th>Assigned To</th>
                <th>Location</th>
                <th>Vendor</th>
                <th>Purchase Date</th>
                <th>Price</th>
                <th>Warranty End</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData['assets'] as $asset)
            <tr>
                <td>{{ $asset['asset_code'] ?? $asset->asset_code ?? '' }}</td>
                <td>{{ Str::limit($asset['name'] ?? $asset->name ?? '', 30) }}</td>
                <td>{{ ucfirst($asset['asset_type'] ?? $asset->asset_type ?? '') }}</td>
                <td>{{ $asset['brand'] ?? $asset->brand ?? '' }}</td>
                <td>
                    @php
                        $status = $asset['status'] ?? $asset->status ?? '';
                        $badgeClass = match($status) {
                            'deployed' => 'badge-green',
                            'maintenance' => 'badge-yellow',
                            'retired' => 'badge-red',
                            'disposed' => 'badge-red',
                            default => 'badge-blue',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                </td>
                <td>{{ ucfirst($asset['condition'] ?? $asset->condition ?? '') }}</td>
                <td>{{ $asset['assigned_to'] ?? ($asset->assignedToUser?->name ?? '') }}</td>
                <td>{{ $asset['location'] ?? $asset->location ?? '' }}</td>
                <td>{{ $asset['vendor'] ?? ($asset->vendor?->name ?? '') }}</td>
                <td>{{ $asset['purchase_date'] ?? ($asset->purchase_date?->format('d/m/Y') ?? '') }}</td>
                <td class="currency">{{ $asset['price'] ? 'Rp ' . number_format($asset['price'], 0, ',', '.') : '-' }}</td>
                <td>{{ $asset['warranty_end'] ?? ($asset->warranty_end?->format('d/m/Y') ?? '') }}</td>
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
