<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 25px; }
        .header { text-align:center; margin-bottom:25px; }
        .header img { margin-bottom:8px; }
        .business-name { font-size:22px; font-weight:bold; margin:0; }
        .report-title { font-size:16px; font-weight:bold; margin:5px 0 20px 0; }
        .section-title { font-size:15px; font-weight:bold; margin-bottom:8px; }

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
        <p class="report-title">Stock List Report ({{ ucfirst($filter) }})</p>

    </div>
    <p class="section-title">Stock List Summary</p>

    @php
        $grandTotal = 0;
        foreach ($inventory as $inv) {
            $grandTotal += ($inv['item']->item_price * $inv['total_quantity']);
        }
    @endphp

    <table>
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Total Quantity</th>
                <th>Price</th>
                <th>Total Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($inventory as $inv)
            <tr>
                <td>{{ $inv['item']->item_name }}</td>
                <td style="text-align:center;">{{ $inv['total_quantity'] }}</td>
                <td style="text-align:right;">{{ number_format($inv['item']->item_price, 2) }}</td>
                <td style="text-align:right;">
                    {{ number_format($inv['item']->item_price * $inv['total_quantity'], 2) }}
                </td>
            </tr>
            @endforeach
               {{-- TOTAL INCOME ROW --}}
                <tr>
                    <td colspan="3" style="text-align:right; font-weight:bold; background:#f4f4f4;">
                        TOTAL INCOME:
                    </td>
                    <td style="text-align:right; font-weight:bold; background:#f4f4f4;">
                        {{ number_format($grandTotal, 2) }}
                    </td>
                </tr>
        </tbody>
    </table>

    <div class="footer"> @ GreenPrints Generated on {{ now()->format('M d, Y h:i A') }}</div>

</body>
</html>
