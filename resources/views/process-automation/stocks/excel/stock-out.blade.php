<table style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr style="font-weight: bold;">
            <td colspan="6" style="text-align:center; border:1px solid #000;">GreenPrints</td>
        </tr>
        <tr>
            <td colspan="6" style="text-align:center; border:1px solid #000;">Stock Out Report</td>
        </tr>
        <tr style="background: #d9ead3; font-weight: bold;">
            <th style="border:1px solid #000;">ID</th>
            <th style="border:1px solid #000;">Item</th>
            <th style="border:1px solid #000;">Quantity</th>
            <th style="border:1px solid #000;">Remarks</th>
            <th style="border:1px solid #000;">Date</th>
            <th style="border:1px solid #000;">User</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($records as $out)
        <tr>
            <td style="border:1px solid #000;">{{ $out->id }}</td>
            <td style="border:1px solid #000;">{{ $out->item->item_name }}</td>
            <td style="border:1px solid #000; text-align:center;">{{ $out->quantity }}</td>
            <td style="border:1px solid #000;">{{ $out->remarks }}</td>
            <td style="border:1px solid #000;">{{ \Carbon\Carbon::parse($out->date)->format('M d, Y') }}</td>
            <td style="border:1px solid #000;">{{ $out->user->name }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
