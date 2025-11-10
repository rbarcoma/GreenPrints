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

    {{-- Carmera in user start part --}}
    <div class="modal fade"  id="startCamera" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true" >
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
                    <!-- ✅ stock type dropdown here -->
                    <div class="d-flex align-items-center mt-3">
                        <label class="mr-2 font-weight-bold">Type:</label>
                        <select id="stockType" class="form-control w-25">
                            <option value="in">Stock In</option>
                            <option value="out">Stock Out</option>
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
                                <th style="width: 120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                     <button type="submit" class="btn btn-success">Save changes</button>
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
                            <label for="formGroupExampleInput" class="form-group">Remarks</label>
                            <textarea class="form-control" placeholder="Leave a remarks here" id="floatingTextarea" name="remarks"></textarea>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


      <!-- Create Modal -->
    <div class="modal fade" id="stockOutModal" tabindex="-1" role="dialog" aria-labelledby="stockOutModal" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockOutModalLabel">Stock In</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>

                <form action="{{ route('stock.stockIn') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Type</label>
                            <input type="text" class="form-control" value="Stock Out" required name="type" readonly>
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
                            <label for="formGroupExampleInput" class="form-group">Remarks</label>
                            <textarea class="form-control" placeholder="Leave a remarks here" id="floatingTextarea" name="remarks"></textarea>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
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
        <div class="table-responsive">
            <table id="StockInTable" class="table table-bordered table-striped mb-0">
                   <thead class="thead-light">
                    <tr>
                        <th>Id</th>
                        <th>Item Name</th>
                        <th>type</th>
                        <th>Quantity </th>
                        <th>remarks</th>
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
                        <td>{{ $in->user->name }}</td>
                     </tr>
                 @endforeach
                </tbody>
            </table>
        </div>
    </section>


    <section class="card flex-fill border p-3" style="min-width: 48%;">
        <h5 class="text-center mb-3">Stock Out Records</h5>
        <div class="table-responsive">
            <table id="StockOutTable" class="table table-bordered table-striped mb-0">
                   <thead class="thead-light">
                    <tr>
                        <th>Id</th>
                        <th>Item Name</th>
                        <th>type</th>
                        <th>Quantity </th>
                        <th>remarks</th>
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
                constrainsts: {
                    width: 320,
                    height: 240,
                    facingMode: "environment"
                }
            },
            locator: {
                patchSize: "medium", // or "small" for faster detection
                halfSample: true
            },
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
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${data.barcode}</td>
                        <td contenteditable="false">${data.item_name}</td>
                        <td contenteditable="false">${data.price}</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn">Edit</button>
                            <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                        </td>
                    `;
                    itemList.appendChild(row);
                } else {
                    alert('❌ Item not found: ' + barcode);
                }
            })
            .catch(err => console.error("Fetch error:", err));
    }

    // --- ACTION BUTTONS (Edit & Delete) ---
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
        const editBtn = row.querySelector('.edit-btn');

        if (editBtn.textContent === "Edit") {
            nameCell.contentEditable = "true";
            priceCell.contentEditable = "true";
            nameCell.focus();
            editBtn.textContent = "Save";
            editBtn.classList.replace('btn-warning', 'btn-success');
        } else {
            nameCell.contentEditable = "false";
            priceCell.contentEditable = "false";
            editBtn.textContent = "Edit";
            editBtn.classList.replace('btn-success', 'btn-warning');

            // You can later send update to backend here
            console.log("Saved:", {
                barcode: row.cells[0].textContent,
                name: nameCell.textContent,
                price: priceCell.textContent
            });
        }
    }
});



$(function () {
    $('#StockInTable').DataTable({
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
        pageLength: 10,
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
        }
    });
});
</script>


@stop

{{--

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

                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${data.barcode}</td>
                        <td contenteditable="false">${data.item_name}</td>
                        <td contenteditable="false">${data.price}</td>
                        <td contenteditable="false" class="qty-cell">0</td> <!-- ✅ Quantity default = 0 -->
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn">Edit</button>
                            <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                        </td>
                    `;
                    itemList.appendChild(row);

                } else {
                    alert('❌ Item not found: ' + barcode);
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
                barcode: row.cells[0].textContent,
                name: row.cells[1].textContent,
                price: row.cells[2].textContent,
                qty: row.cells[3].textContent,
                type: stockType.value // stock in / out
            });
        });

        console.log("Saving...", items);

        fetch(`/stocks/save`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ items: items })
        })
        .then(res => res.json())
        .then(response => {
            alert("Saved successfully!");
            location.reload();
        })
        .catch(err => console.error(err));
    });

});

</script>
@stop --}}

@section('css')
<style>
    #reader video {
        width: 100% !important;
        height: auto !important;
        max-height: 200px !important;
        object-fit: contain !important;
    }
</style>

@endsection
