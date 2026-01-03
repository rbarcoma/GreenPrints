<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 25px; }
        .header { text-align:center; margin-bottom:25px; }
        .header img { margin-bottom:8px; }
        .business-name { font-size:22px; font-weight:bold; margin:0; }
        .report-title { font-size:16px; font-weight:bold; margin:5px 0 20px 0; }

        table { width:100%; border-collapse:collapse; margin-top:10px; }
        th { background:#e8f5e9; border:1px solid #555; padding:8px; text-align:center; }
        td { border:1px solid #777; padding:7px; }

        .footer { margin-top:25px; text-align:center; font-size:10px; color:#777; }
    </style>
</head>

<body>

    <div class="header">
        @if(file_exists(public_path('logo.png')))
            <img src="{{ public_path('logo.png') }}" height="70">
        @endif
        <p class="business-name">GreenPrints</p>
        <p class="report-title">Stock Out Report ({{ ucfirst($filter) }})</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Remarks</th>
                <th>Date</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $out)
            <tr>
                <td>{{ $out->id }}</td>
                <td>{{ $out->item->item_name }}</td>
                <td style="text-align:center;">{{ $out->quantity }}</td>
                <td>{{ $out->remarks }}</td>
                <td>{{ \Carbon\Carbon::parse($out->date)->format('M d, Y') }}</td>
                <td>{{ $in->user->name ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">@ GreenPrints Generated on {{ now()->format('M d, Y h:i A') }}</div>

</body>
</html>
