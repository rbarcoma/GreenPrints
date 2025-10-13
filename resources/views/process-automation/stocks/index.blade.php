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
                        <button type="submit" class="btn btn-primary">Save changes</button>
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
                        <button type="submit" class="btn btn-primary">Save changes</button>
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

    $('#StockInTable').DataTable({
        responsive: true,
        autoWidth: true,
        pageLength: 10,
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
        }
    });

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
