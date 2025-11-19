<table style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr style="font-weight: bold;">
            <td colspan="4" style="text-align:center; border:1px solid #000;">GreenPrints</td>
        </tr>
        <tr>
            <td colspan="4" style="text-align:center; border:1px solid #000;">Stock List Report</td>
        </tr>
        <tr style="background: #d9ead3; font-weight: bold;">
            <th style="border: 1px solid #000;">Item Name</th>
            <th style="border: 1px solid #000;">Total Quantity</th>
            <th style="border: 1px solid #000;">Price</th>
            <th style="border: 1px solid #000;">Total Value</th>
        </tr>
    </thead>

    <tbody>
        @php $grandTotal = 0; @endphp

        @foreach ($inventory as $inv)
            @php
                $value = $inv['item']->item_price * $inv['total_quantity'];
                $grandTotal += $value;
            @endphp

            <tr>
                <td style="border: 1px solid #000;">{{ $inv['item']->item_name }}</td>
                <td style="border: 1px solid #000; text-align:center;">{{ $inv['total_quantity'] }}</td>
                <td style="border: 1px solid #000; text-align:center;">{{ number_format($inv['item']->item_price, 2) }}</td>
                <td style="border: 1px solid #000; text-align:center;">{{ number_format($value, 2) }}</td>
            </tr>
        @endforeach

        {{-- TOTAL INCOME --}}
        <tr style="font-weight: bold;">
            <td colspan="3" style="text-align:right; border:1px solid #000;">TOTAL INCOME</td>
            <td style="border: 1px solid #000; text-align:center;">{{ number_format($grandTotal, 2) }}</td>
        </tr>
    </tbody>
</table>
