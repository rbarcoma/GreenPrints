<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20mm 10mm; /* top, bottom, left, right margins */
        }

        td {
            width: 50mm;
            height: 25mm;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: middle;
            padding: 0;
        }

        .label-name {
            font-size: 10px;
            margin-bottom: 2px;
        }

        .barcode-text {
            font-size: 9px;
            margin-top: 2px;
        }

        .page-break {
            page-break-after: always;
        }

    </style>
</head>
<body>

@php
    $perPage = 24;
    $chunks = array_chunk($barcodes, 24);
@endphp

@foreach ($chunks as $pageIndex => $pageItems)

<table>
    @for ($row = 0; $row < 8; $row++)
        <tr>
            @for ($col = 0; $col < 3; $col++)
                @php
                    $i = $row * 3 + $col;
                @endphp

                @if(isset($pageItems[$i]))
                    <td>
                        <div class="label-name">{{ $pageItems[$i]['name'] }}</div>
                        {!! $pageItems[$i]['barcode_html'] !!}
                        <div class="barcode-text">{{ $pageItems[$i]['barcode'] }}</div>
                    </td>
                @else
                    <td></td>
                @endif
            @endfor
        </tr>
    @endfor
</table>

@if(!$loop->last)
    <div class="page-break"></div>
@endif

@endforeach

</body>
</html>
