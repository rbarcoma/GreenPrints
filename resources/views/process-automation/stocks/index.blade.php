@extends('adminlte::page')

@section('title', 'Item Category')

@section('content_header')
    <h1>Stocks Record</h1>
@stop

@section('content')
<section class="border p-3 card">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Stock List</h4>
        <div class="">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">
                 Stock In
            </button>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#stockOutModal">
                Stock Out
            </button>

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#startCamera">Use Scanner</button>
        </div>
    </div>
    <div class="dropdown mb-3">
        <button class="btn btn-dark dropdown-toggle" data-toggle="dropdown">
            Export Stock List
        </button>
        <div class="dropdown-menu">
            <a class="dropdown-item pdf" href="{{ route('stock.export.list.pdf', ['filter' => 'weekly']) }}">PDF Weekly</a>
            <a class="dropdown-item pdf" href="{{ route('stock.export.list.pdf', ['filter' => 'monthly']) }}">PDF Monthly</a>
            <a class="dropdown-item pdf" href="{{ route('stock.export.list.pdf', ['filter' => 'yearly']) }}">PDF Yearly</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item excel" href="{{ route('stock.export.list.excel', ['filter' => 'weekly']) }}">Excel Weekly</a>
            <a class="dropdown-item excel" href="{{ route('stock.export.list.excel', ['filter' => 'monthly']) }}">Excel Monthly</a>
            <a class="dropdown-item excel" href="{{ route('stock.export.list.excel', ['filter' => 'yearly']) }}">Excel Yearly</a>
        </div>
    </div>

    {{-- Carmera in user start part --}}
    <div class="modal fade"  id="startCamera" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true" >
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
             <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="useCameraLabel">Camera Scanner</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <button id="useCameraBtn" class="btn btn-success">Use Camera</button>
                    <button id="useScannerBtn" class="btn btn-primary">Use Scanner</button>
                    <div class="d-flex align-items-center mt-3">
                        <label class="mr-2 font-weight-bold">Type:</label>
                        <select id="stockType" class="form-control w-25">
                            <option value="Stock In">Stock In</option>
                            <option value="Stock Out">Stock Out</option>
                        </select>
                    </div>
                    <input type="text" id="barcodeInput" class="form-control mt-2" placeholder="Scan barcode..." autofocus>

                <div id="reader" style="width: 250px; height: 180px; display:none;overflow:hidden" class="viewport mx-auto"></div>


                    <table class="table table-bordered mt-3" id="item-list">
                        <thead>
                            <tr>
                                <th>Barcode</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Remarks</th>
                                <th>Date</th>
                                <th style="width: 120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                     <button type="submit" class="btn btn-success" id="saveChangesBtn">Save changes</button>
                </div>
            </div>
        </div>
    </div>




    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Stock In</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>

                <form action="{{ route('stock.stockIn') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Type</label>
                            <input type="text" class="form-control" value="Stock In" required name="type" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="formGroupExampleInput" class="form-group">Item Name</label>
                              <select class="custom-select" id="inputGroupSelect01" name="item">
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}">{{ $item->item_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Item Quantity</label>
                            <input type="number" class="form-control" placeholder="example: 10" required name="quantity">
                        </div>

                         <div class="mb-3">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>

                         <div class="mb-3">
                            <label for="formGroupExampleInput" class="form-group">Remarks</label>
                            <textarea class="form-control" placeholder="Leave a remarks here" id="floatingTextarea" name="remarks"></textarea>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Add Stock In</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

      <!-- Stock out Create Modal -->
    <div class="modal fade" id="stockOutModal" tabindex="-1" role="dialog" aria-labelledby="stockOutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-sm">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockOutModalLabel">Stock Out</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>

                <form action="{{ route('stock.stockIn') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Type</label>
                            <input type="text" class="form-control" value="Stock Out" name="type" readonly>
                        </div>

                        <div class="form-group">
                            <label>Item Name</label>
                            <select class="custom-select" id="inputGroupSelect01" name="item" required>
                                @php
                                    $stocks = \App\Models\StockModel::with('item')->get();
                                    $inventory = $stocks->groupBy('item_id')->map(function ($group) {
                                        $total = 0;
                                        foreach ($group as $stock) {
                                            $total += $stock->type === 'Stock In'
                                                ? $stock->quantity
                                                : -$stock->quantity;
                                        }
                                        return [
                                            'item' => $group->first()->item,
                                            'total_quantity' => $total,
                                        ];
                                    });
                                    $availableForStockOut = collect($inventory)->filter(fn($data) => $data['total_quantity'] > 0);
                                @endphp

                                @forelse ($availableForStockOut as $data)
                                    @continue(!$data['item'])   {{-- Skip if the item was deleted --}}
                                    <option value="{{ $data['item']->id }}">
                                        {{ $data['item']->item_name }} — (Available: {{ $data['total_quantity'] }})
                                    </option>
                                @empty
                                    <option disabled>No items available for Stock Out</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Item Quantity</label>
                            <input type="number" class="form-control" placeholder="example: 10" required name="quantity" min="1">
                        </div>

                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea class="form-control" placeholder="Leave a remark here" name="remarks"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success"
                            {{ $availableForStockOut->isEmpty() ? 'disabled' : '' }}>
                            Add Stock Out
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <table class="table-responsive">
            <table id="itemCategoryTable" class="table table-bordered table-striped">
                <thead class="thead-light">
                    <tr>
                        <th>Item Name</th>
                        <th>Stocks Record </th>
                    </tr>
                </thead>
                <tbody>
                 @foreach($inventory as $inv)
                     <tr>
                         <td>{{ $inv['item']->item_name }}</td>
                        <td>{{ $inv['total_quantity'] }}</td>
                     </tr>
                 @endforeach
                </tbody>
            </table>
     </table>


</section>

<div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mt-4">
      <section class="card flex-fill border p-3" style="min-width: 48%;">
        <h5 class="text-center mb-3">Stock In Records</h5>
        <div class="dropdown mb-3">
            <button class="btn btn-dark dropdown-toggle" data-toggle="dropdown">
                Export Stock In
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item pdf" href="{{ route('stock.export.in.pdf', ['filter' => 'weekly']) }}">PDF Weekly</a>
                <a class="dropdown-item pdf" href="{{ route('stock.export.in.pdf', ['filter' => 'monthly']) }}">PDF Monthly</a>
                <a class="dropdown-item pdf" href="{{ route('stock.export.in.pdf', ['filter' => 'yearly']) }}">PDF Yearly</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item excel" href="{{ route('stock.export.in.excel', ['filter' => 'weekly']) }}">Excel Weekly</a>
                <a class="dropdown-item excel" href="{{ route('stock.export.in.excel', ['filter' => 'monthly']) }}">Excel Monthly</a>
                <a class="dropdown-item excel" href="{{ route('stock.export.in.excel', ['filter' => 'yearly']) }}">Excel Yearly</a>
            </div>
        </div>
        <div class="table-responsive">
            <table id="StockInTable" class="table table-bordered table-striped mb-0">
                   <thead class="thead-light">
                    <tr>
                        <th>Id</th>
                        <th>Item Name</th>
                        <th>type</th>
                        <th>Quantity </th>
                        <th>remarks</th>
                        <th>Date</th>
                        <th>Responsible User</th>
                    </tr>
                </thead>
                <tbody>
                 @foreach($stockIn as $in)
                     <tr>
                        <td>{{ $in->id }}</td>
                        <td>{{ $in->item->item_name }}</td>
                        <td>{{ $in->type }}</td>
                        <td>{{ $in->quantity }}</td>
                        <td>{{ $in->remarks }}</td>
                        <td>{{ \Carbon\Carbon::parse($in->date)->format('M d, Y') }}</td>
                        <td>{{ $in->user->name }}</td>
                     </tr>
                 @endforeach
                </tbody>
            </table>
        </div>
    </section>


    <section class="card flex-fill border p-3" style="min-width: 48%;">
        <h5 class="text-center mb-3">Stock Out Records</h5>
        <div class="dropdown mb-3">
            <button class="btn btn-dark dropdown-toggle" data-toggle="dropdown">
                Export Stock Out
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item pdf" href="{{ route('stock.export.out.pdf', ['filter' => 'weekly']) }}">PDF Weekly</a>
                <a class="dropdown-item pdf" href="{{ route('stock.export.out.pdf', ['filter' => 'monthly']) }}">PDF Monthly</a>
                <a class="dropdown-item pdf" href="{{ route('stock.export.out.pdf', ['filter' => 'yearly']) }}">PDF Yearly</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item excel" href="{{ route('stock.export.out.excel', ['filter' => 'weekly']) }}">Excel Weekly</a>
                <a class="dropdown-item excel" href="{{ route('stock.export.out.excel', ['filter' => 'monthly']) }}">Excel Monthly</a>
                <a class="dropdown-item excel" href="{{ route('stock.export.out.excel', ['filter' => 'yearly']) }}">Excel Yearly</a>
            </div>
        </div>
        <div class="table-responsive">
            <table id="StockOutTable" class="table table-bordered table-striped mb-0">
                   <thead class="thead-light">
                    <tr>
                        <th>Id</th>
                        <th>Item Name</th>
                        <th>type</th>
                        <th>Quantity </th>
                        <th>remarks</th>
                        <th>Date</th>
                        <th>Responsible User</th>
                    </tr>
                </thead>
                <tbody>
                 @foreach($stockOut as $out)
                     <tr>
                        <td>{{ $out->id }}</td>
                        <td>{{ $out->item->item_name }}</td>
                        <td>{{ $out->type }}</td>
                        <td>{{ $out->quantity }}</td>
                        <td>{{ $out->remarks }}</td>
                        <td>{{ \Carbon\Carbon::parse($out->date)->format('M d, Y') }}</td>
                        <td>{{ $out->user->name }}</td>
                     </tr>
                 @endforeach
                </tbody>
            </table>
            </table>
        </div>
    </section>
</div>

@stop

@section('js')

<script>

document.addEventListener('DOMContentLoaded', function() {

    const barcodeInput = document.getElementById('barcodeInput');
    const readerDiv = document.getElementById('reader');
    const itemList = document.querySelector('#item-list tbody');
    const useCameraBtn = document.getElementById('useCameraBtn');
    const useScannerBtn = document.getElementById('useScannerBtn');
    const stockType = document.getElementById('stockType'); // TYPE: Stock In / Out dropdown

    let cameraActive = false;

    // --- SWITCH TO BARCODE SCANNER MODE ---
    useScannerBtn.addEventListener('click', () => {
        if (cameraActive) stopCamera();
        readerDiv.style.display = 'none';
        barcodeInput.style.display = 'block';
        barcodeInput.focus();
    });

    // --- SWITCH TO CAMERA MODE ---
    useCameraBtn.addEventListener('click', () => {
        barcodeInput.style.display = 'none';
        readerDiv.style.display = 'block';
        startCameraScanner();
    });

    // --- HARDWARE SCANNER MODE ---
    barcodeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const code = barcodeInput.value.trim();
            if (code) handleScannedCode(code);
            barcodeInput.value = '';
        }
    });

    // --- CAMERA SCANNER MODE (QuaggaJS) ---
    function startCameraScanner() {
        if (cameraActive) return;

        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: document.querySelector('#reader'),
                constraints: {
                    width: 320,
                    height: 240,
                    facingMode: "environment"
                }
            },
            locator: { patchSize: "medium", halfSample: true },
            decoder: { readers: ["code_128_reader", "ean_reader", "ean_8_reader"] },
            locate: true
        }, function(err) {
            if (err) { console.error("Camera init error:", err); return; }
            Quagga.start();
            cameraActive = true;
            console.log("Camera ready.");
        });

        Quagga.onDetected(data => {
            const barcode = data.codeResult.code;
            Quagga.pause();
            handleScannedCode(barcode);
            setTimeout(() => Quagga.start(), 1500);
        });
    }

    function stopCamera() {
        if (cameraActive) {
            Quagga.stop();
            cameraActive = false;
        }
    }

    // --- MAIN HANDLER ---
    function handleScannedCode(barcode) {
        console.log("Scanned:", barcode);

        fetch(`/get-item/${barcode}`)
            .then(res => res.json())
            .then(data => {
                if (data && data.barcode) {
                    const existingRow = Array.from(itemList.children).find(row =>
                        row.cells[0].textContent.trim() === data.barcode.trim()
                    );

                    if (existingRow) {
                        const qtyInput = existingRow.querySelector('.qty-cell input');
                        let currentQty = parseInt(qtyInput.value) || 0;
                        qtyInput.value = currentQty + 1;

                        qtyCell.style.backgroundColor = "#d4edda";
                        setTimeout(() => qtyCell.style.backgroundColor = "", 500);
                    } else {
                        const row = document.createElement('tr');
                          row.dataset.itemId = data.item_id;
                        row.innerHTML = `
                            <td>${data.barcode}</td>
                            <td contenteditable="false">${data.item_name}</td>
                            <td contenteditable="false">${data.price}</td>
                            <td contenteditable="false" class="qty-cell">
                                <input type="text" class="form-control form-control-sm qty-input" value="1">
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm remarks-input" placeholder="Enter remarks">
                            </td>
                            <td>
                                <input type="date" class="form-control form-control-sm date-input"
                                value="${new Date().toISOString().split('T')[0]}">
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-btn">Edit</button>
                                <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                            </td>
                        `;
                        itemList.appendChild(row);

                    }
                } else {
                    alert('Item not found: ' + barcode);
                }
            })
            .catch(err => console.error("Fetch error:", err));
    }

    // --- ACTION BUTTONS ---
    itemList.addEventListener('click', function(e) {
        const row = e.target.closest('tr');

        if (e.target.classList.contains('delete-btn')) {
            row.remove();
        }

        if (e.target.classList.contains('edit-btn')) {
            toggleEditRow(row);
        }
    });

    function toggleEditRow(row) {
        const nameCell = row.cells[1];
        const priceCell = row.cells[2];
        const qtyCell = row.cells[3];
        const editBtn = row.querySelector('.edit-btn');

        if (editBtn.textContent === "Edit") {
            nameCell.contentEditable = "true";
            priceCell.contentEditable = "true";
            qtyCell.contentEditable = "true";

            editBtn.textContent = "Save";
            editBtn.classList.replace('btn-warning', 'btn-success');
        } else {
            nameCell.contentEditable = "false";
            priceCell.contentEditable = "false";
            qtyCell.contentEditable = "false";

            editBtn.textContent = "Edit";
            editBtn.classList.replace('btn-success', 'btn-warning');
        }
    }



    // SAVE CHANGES BUTTON HANDLER
    document.getElementById('saveChangesBtn').addEventListener('click', function() {

        const items = [];

        itemList.querySelectorAll('tr').forEach(row => {
            items.push({
                item_id: row.dataset.itemId,
                type: stockType.value,
                qty: row.querySelector('.qty-input')?.value || 1,
                date: row.querySelector('.date-input')?.value || '',
                remarks: row.querySelector('.remarks-input')?.value || '',
            });
        });

        console.log("Saving...", items);

        // use AJAX

        $.ajax({
            type: "POST",
            url: "{{ route('scannerInsert') }}",
            data: {
                _token: "{{ csrf_token() }}",
                items: items
            },
            success: function (response) {
                alert("Stocks saved successfully!");
                location.reload();
                $('#item-list tbody').empty();
                $('#startCamera').modal('hide');
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                alert("Error saving!");
            }
        });

        // fetch(`/scannerStock`, {
        //     method: "POST",
        //     headers: {
        //         "Content-Type": "application/json",
        //         "X-CSRF-TOKEN": "{{ csrf_token() }}"
        //     },
        //     body: JSON.stringify({ items: items })
        // })
        // .then(res => res.json())
        // .then(response => {
        //     alert("Saved successfully!");
        //     location.reload();
        // })
        // .catch(err => console.error(err));
    });

});

$(function () {
    $('#StockInTable').DataTable({
        responsive: true,
        autoWidth: true,
        pageLength: 5,
        lengthMenu: [ [5, 10, 25, 50, 100], [5, 10, 25, 50, 100] ],
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
        }
    });
});

$(function () {
    $('#itemCategoryTable').DataTable({
        responsive: true,
        autoWidth: true,
        pageLength: 10,
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
        }
    });
});

$(function () {
    $('#StockOutTable').DataTable({
        responsive: true,
        autoWidth: true,
        pageLength: 5,
        lengthMenu: [ [5, 10, 25, 50, 100], [5, 10, 25, 50, 100] ],
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
        }
    });
});

</script>
@stop

@section('css')
<style>
    #reader video {
        width: 100% !important;
        height: auto !important;
        max-height: 200px !important;
        object-fit: contain !important;
    }
</style>

<style>

.dropdown-menu .dropdown-item.pdf:hover,
.dropdown-menu .dropdown-item.pdf:focus {
    background-color: #dc3545 !important;
    color: #fff !important;
}

.dropdown-menu .dropdown-item.excel:hover,
.dropdown-menu .dropdown-item.excel:focus {
    background-color: #198754 !important;
    color: #fff !important;
}
</style>

@endsection
