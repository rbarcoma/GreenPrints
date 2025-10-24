
@extends('adminlte::page')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}

<p class="text-muted">Manage your inventory items</p>


<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6>Total Inventory Value</h6>
                <h4 class="fw-bold text-success">₱ {{ number_format($totalValue, 2) }}</h4>
                <i class="bi bi-graph-up text-secondary"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6>Total Available Items</h6>
                <h4 class="fw-bold">{{ $availableItems }}</h4>
                <i class="bi bi-box text-secondary"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6>Low Stock Items</h6>
                <h4 class="fw-bold text-warning">{{ $lowStock }}</h4>
                <i class="bi bi-exclamation-triangle text-secondary"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6>Out of Stock Items</h6>
                <h4 class="fw-bold text-danger">{{ $outStock }}</h4>
                <i class="bi bi-x-circle text-secondary"></i>
            </div>
        </div>
    </div>
</div>

{{-- Inventory Items Table --}}

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex align-items-center">
        <h6 class="fw-bold m-0 flex-grow-1 font-weight-bold">Inventory Items Table</h6>
        <button class="btn btn-dark btn-sm px-3" data-bs-toggle="modal" data-bs-target="#addItemModal">
           Add Item
        </button>
    </div>
    <div class="card-body">
        <div class="d-flex mb-3 gap-2">
            <input type="text" class="form-control" placeholder="Search items...">
            <select class="form-select w-auto">
                <option>All Types</option>
            </select>
            <select class="form-select w-auto">
                <option>All Status</option>
            </select>
        </div>
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Item Category</th>
                    <th>Type</th>
                    <th>Quantity Unit</th>
                    <th>Total Stock</th>
                    <th>Reorder Level</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->item_category }}</td>
                        <td>{{ $item->type }}</td>
                        <td>{{ $item->quantity_unit }}</td>
                        <td>{{ $item->total_stock }}</td>
                        <td>{{ $item->reorder_level }}</td>
                        <td>
                            @if($item->status === 'Available')
                                <span class="badge bg-success">{{ $item->status }}</span>
                            @elseif($item->status === 'Low Stock')
                                <span class="badge bg-warning text-dark">{{ $item->status }}</span>
                            @else
                                <span class="badge bg-danger">{{ $item->status }}</span>
                            @endif
                        </td>
                        <td>
                            <!-- EDIT -->
                            <button class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editItemModal{{ $item->id }}">
                                EDIT
                            </button>

                            <!-- DELETE -->
                            <form action="{{ route('inventory.deleteItem', $item->id) }}"
                                method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Delete this item?')">
                                    DELETE
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- EDIT MODAL -->
                    <div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1"
                        aria-labelledby="editItemModalLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-0 shadow-sm">
                                <form action="{{ route('inventory.updateItem', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-header bg-dark text-white">
                                        <h5 class="modal-title" id="editItemModalLabel{{ $item->id }}">Edit Inventory Item</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Item Category</label>
                                            <input type="text" name="item_category" value="{{ $item->item_category }}" class="form-control" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Type</label>
                                            <input type="text" name="type" value="{{ $item->type }}" class="form-control" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Quantity Unit</label>
                                            <input type="text" name="quantity_unit" value="{{ $item->quantity_unit }}" class="form-control" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Total Stock</label>
                                            <input type="number" name="total_stock" value="{{ $item->total_stock }}" class="form-control" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Reorder Level</label>
                                            <input type="number" name="reorder_level" value="{{ $item->reorder_level }}" class="form-control" min="1" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-select">
                                                <option value="Available" {{ $item->status === 'Available' ? 'selected' : '' }}>Available</option>
                                                <option value="Low Stock" {{ $item->status === 'Low Stock' ? 'selected' : '' }}>Low Stock</option>
                                                <option value="Out of Stock" {{ $item->status === 'Out of Stock' ? 'selected' : '' }}>Out of Stock</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-dark">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Most Used Stocks --}}
<div class="card shadow-sm mb-4 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h6 class="fw-bold m-0">Most Used Stocks</h6>
      <i class="bi bi-graph-up-arrow fs-5 text-success"></i>
    </div>

    @if($mostUsed->isEmpty())
      <p class="text-muted mb-0">No usage data yet...</p>
    @else
      <div class="table-responsive" style="white-space: nowrap;">
        <div class="d-flex flex-row ">
          @foreach ($mostUsed->chunk(5) as $chunk)
            <div class="ml-7 pl-3"> {{-- 👈 added space between columns --}}
              @foreach ($chunk as $index => $m)
                <div class="small mb-2"> {{-- 👈 slight spacing between items --}}
                  <span class="fw-semibold">{{ $loop->parent->index * 5 + $loop->iteration }}.</span>
                  <span>{{ $m->item->item_category ?? 'Unknown' }}</span>
                  <span class="text-muted">— {{ $m->total_quantity }} used</span>
                </div>
              @endforeach
            </div>
          @endforeach
        </div>
      </div>
    @endif
  </div>
</div>

{{-- Inventory Batch Items Table --}}

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="fw-bold m-0 flex-grow-1 font-weight-bold">Inventory Batch Items Table</h6>
        <button class="btn btn-dark btn-sm px-2" data-bs-toggle="modal" data-bs-target="#addBatchModal">
           Add Batch Item
        </button>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Item Category</th>
                    <th>Title</th>
                    <th>Quantity</th>
                    <th>Unit Cost</th>
                    <th>Supplier</th>
                    <th>Obtained Date</th>
                    <th>Expiry Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                    @foreach ($batches as $batch)
                        <tr>
                            <td>{{ $batch->item->item_category ?? '—' }}</td>
                            <td>{{ $batch->title }}</td>
                            <td>{{ $batch->quantity }}</td>
                            <td>₱ {{ number_format($batch->unit_cost, 2) }}</td>
                            <td>{{ $batch->supplier ?? '—' }}</td>
                            <td>{{ $batch->obtained_date ?? '—' }}</td>
                            <td>{{ $batch->expiry_date ?? '—' }}</td>
                            <td>
                                <!-- Edit Button -->
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBatchModal{{ $batch->id }}">
                                    EDIT
                                </button>

                                <!-- Delete Button -->
                                <form action="{{ route('inventory.deleteBatch', $batch->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this batch?')">
                                        DELETE
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- EDIT MODAL  -->
                        <div class="modal fade" id="editBatchModal{{ $batch->id }}" tabindex="-1"
                            aria-labelledby="editBatchModalLabel{{ $batch->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-sm">
                                    <form action="{{ route('inventory.updateBatch', $batch->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="modal-header bg-dark text-white">
                                            <h5 class="modal-title" id="editBatchModalLabel{{ $batch->id }}">Edit Batch</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Batch Title</label>
                                                <input type="text" name="title" value="{{ $batch->title }}" class="form-control" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Quantity</label>
                                                <input type="number" name="quantity" value="{{ $batch->quantity }}" class="form-control" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Unit Cost (₱)</label>
                                                <input type="number" step="0.01" name="unit_cost" value="{{ $batch->unit_cost }}" class="form-control" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Supplier</label>
                                                <input type="text" name="supplier" value="{{ $batch->supplier }}" class="form-control">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Obtained Date</label>
                                                <input type="date" name="obtained_date" value="{{ $batch->obtained_date }}" class="form-control">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Expiry Date</label>
                                                <input type="date" name="expiry_date" value="{{ $batch->expiry_date }}" class="form-control">
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-dark">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
        </table>
    </div>
</div>

{{-- Supplier Details Table --}}

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="fw-bold m-0 flex-grow-1 font-weight-bold">Supplier Details Table</h6>
        <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
        <i class="bi bi-plus-lg me-1"></i>Add New Supplier
    </button>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Company Name</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Address</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($suppliers as $supplier)
                    <tr>
                        <td>{{ $supplier->company_name }}</td>
                        <td>{{ $supplier->contact }}</td>
                        <td>{{ $supplier->email }}</td>
                        <td>{{ $supplier->address }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Inventory Activity Log Table --}}

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="fw-bold m-0 font-weight-bold">Inventory Activity Log Table</h6>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>Log ID</th>
                    <th>Inventory ID</th>
                    <th>Batch ID</th>
                    <th>Staff ID</th>
                    <th>Action Type</th>
                    <th>Qty Changed</th>
                    <th>Prev Qty</th>
                    <th>New Qty</th>
                    <th>Unit Cost</th>
                    <th>Total Cost</th>
                    <th>Reason</th>
                    <th>Date Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                    <tr>
                        <td>{{ $log->id }}</td>
                        <td>{{ $log->inventory_id }}</td>
                        <td>{{ $log->batch_id }}</td>
                        <td>{{ $log->staff_id }}</td>
                        <td>{{ $log->action_type }}</td>
                        <td>{{ $log->quantity_changed }}</td>
                        <td>{{ $log->previous_quantity }}</td>
                        <td>{{ $log->new_quantity }}</td>
                        <td>{{ $log->unit_cost }}</td>
                        <td>{{ $log->total_cost }}</td>
                        <td>{{ $log->reason }}</td>
                        <td>{{ $log->date_time }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

     {{-- ADD INVENTORY ITEM MODAL --}}

<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-sm">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="addItemModalLabel">Add Inventory Item</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
    <form action="{{ route('inventory.addItem') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Item Category</label>
            <input type="text" name="item_category" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Type</label>
            <input type="text" name="type" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Quantity Unit</label>
            <input type="text" name="quantity_unit" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Total Stock</label>
            <input type="number" name="total_stock" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Reorder Level</label>
            <input type="number" name="reorder_level" class="form-control" min="1" value="5" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-dark">Save Item</button>
        </div>
      </form>
    </div>
  </div>
</div>

     {{-- ADD INVENTORY BATCH MODAL --}}

<div class="modal fade" id="addBatchModal" tabindex="-1" aria-labelledby="addBatchModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-sm">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="addBatchModalLabel">Add Inventory Batch Item</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
        <form action="{{ route('inventory.addBatch') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Select Item</label>
                    <select name="item_id" class="form-select" required>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}">{{ $item->item_category }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Batch Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Unit Cost (₱)</label>
                    <input type="number" name="unit_cost" class="form-control" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Supplier</label>
                    <input type="text" name="supplier" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Obtained Date</label>
                    <input type="date" name="obtained_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Expiry Date</label>
                    <input type="date" name="expiry_date" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-dark">Save Batch</button>
            </div>
        </form>
    </div>
  </div>
</div>

{{-- ==========================
     ADD SUPPLIER MODAL
========================== --}}
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-sm">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="addSupplierModalLabel">Add New Supplier</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
        <form action="{{ route('inventory.addSupplier') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                <label class="form-label">Company Name</label>
                <input type="text" name="company_name" class="form-control" required>
                </div>
                <div class="mb-3">
                <label class="form-label">Contact</label>
                <input type="text" name="contact" class="form-control" required>
                </div>
                <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-dark">Save Supplier</button>
            </div>
        </form>
    </div>
  </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ✅ Add Item
    const addItemForm = document.getElementById('addItemForm');
    if (addItemForm) {
        addItemForm.addEventListener('submit', function(e) {
            e.preventDefault();
            fetch("{{ route('inventory.addItem') }}", {
                method: "POST",
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: new FormData(this)
            }).then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Item added successfully!');
                    location.reload();
                }
            });
        });
    }

    // ✅ Add Batch
    const addBatchForm = document.getElementById('addBatchForm');
    if (addBatchForm) {
        addBatchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            fetch("{{ route('inventory.addBatch') }}", {
                method: "POST",
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: new FormData(this)
            }).then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Batch added successfully!');
                    location.reload();
                }
            });
        });
    }

    // ✅ Add Supplier
    const addSupplierForm = document.getElementById('addSupplierForm');
    if (addSupplierForm) {
        addSupplierForm.addEventListener('submit', function(e) {
            e.preventDefault();
            fetch("{{ route('inventory.addSupplier') }}", {
                method: "POST",
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: new FormData(this)
            }).then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Supplier added successfully!');
                    location.reload();
                }
            });
        });
    }

    // ✅ Most Used Stocks Chart
    const ctx = document.getElementById('mostUsedChart');
    if (ctx) {
        const labels = @json($mostUsed->pluck('item.item_category'));
        const data = @json($mostUsed->pluck('total_quantity'));

        if (labels.length && data.length) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Quantity Used',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }
    }
});
</script>
@endpush


@endsection
