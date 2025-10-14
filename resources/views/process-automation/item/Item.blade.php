@extends('adminlte::page')

@section('title', 'Item Category')

@section('content_header')
    <h1>Item </h1>
@stop

@section('content')
<section class="border p-3 card">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Item List</h4>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">
            Create Item
        </button>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Item Category Creation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>

                <form action="{{ route('item.create') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Item Title</label>
                            <input type="text" class="form-control" placeholder="Please input category title" required name="name">
                        </div>

                        <div class="mb-3">
                            <label for="formGroupExampleInput" class="form-group">Item Description</label>
                            <textarea class="form-control" placeholder="Leave a description here" id="floatingTextarea" name="description"></textarea>
                        </div>

                        <div class="mb-3">
                                <label for="formGroupExampleInput" class="form-group">Item Category</label>
                              <select class="custom-select" id="inputGroupSelect01" name="category">
                                @foreach ($category as $item)
                                    <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Item price</label>
                            <input type="number" class="form-control" placeholder="Please item price" required name="price" min="0" value="0" step="0.01">
                        </div>

                      <div class="mb-3">
                            <label for="image" class="form-label">Upload item image</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image" accept="image/*"  name="image"  onchange="previewImage(event)">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                      </div>

                        <div>
                            <button id="removeBtn" type="button" class="btn btn-sm btn-danger d-none" onclick="removeImage()">Remove Image</button>
                        </div>

                      <div class="mb-3 text-center">
                        <img id="preview"
                            src="{{ asset('default_image/default_image.jpg') }}"
                            alt="Preview Image"
                            class="img-thumbnail mb-3"
                            style="max-width: 200px;">
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

    <!-- Table -->
    <div class="table-responsive">
        <table id="itemCategoryTable" class="table table-bordered table-striped">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Item Name</th>
                    {{-- <th>Description</th> --}}
                    <th>Category</th>
                    <th>Price</th>
                    <th>Barcode</th>
                    <th>Image</th>
                    <th>Action</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($item_collection as $collection)
                    <tr>
                        <td>{{ $collection->id }}</td>
                        <td>{{ $collection->item_name }}</td>
                        {{-- <td>{{ $collection->item_desc }}</td> --}}
                        <td>{{ $collection->category->category_name }}</td>

                        <td>{{ number_format($collection->item_price, 2) }}</td>
                        <td>
                            {!! DNS1D::getBarcodeHTML($collection->barcode->barcode_value, 'EAN13') !!}
                            Barcode: {{ $collection->barcode->barcode_value }}
                        </td>
                        <td>
                            <img src="{{ asset('Item/images/' . $collection->image->image_name) }}" width="80" alt="Item Image"> <br>
                            {{ $collection->image->image_name }}

                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary mr-2" data-toggle="modal" data-target="#viewModal{{ $collection->id }}">view</button>
                        </td>
                        <td>
                            {{ $collection->status }}
                        </td>
                    </tr>

                    <!-- View Modal -->
                    <div class="modal fade" id="viewModal{{ $collection->id }}" tabindex="-1" role="dialog"
                         aria-labelledby="viewModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">View item : {{ $collection->item_name }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span>&times;</span>
                                    </button>
                                </div>

                                    <div class="modal-body">
                                        <div class="form-group">
                                            <img src="{{ asset('Item/images/' . $collection->image->image_name) }}" width="80" alt="Item Image"> <br>
                                            {{ $collection->image->image_name }}
                                        </div>

                                        <div class="form-group">
                                            <label>Barcode</label>
                                            <span>{!! DNS1D::getBarcodeHTML($collection->barcode->barcode_value, 'PHARMA') !!}</span>
                                            <span>Barcode: {{ $collection->barcode->barcode_value }}</span>
                                        </div>


                                        <div class="form-group">
                                            <label>Item Name</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ $collection->item_name }}" disabled>
                                        </div>
                                         <div class="mb-3">
                                            <label for="formGroupExampleInput" class="form-label">Description</label>
                                            <textarea class="form-control" placeholder="Leave a description here" id="floatingTextarea" name="item_desc"  disabled rows="6">{{ $collection->item_desc }}</textarea>
                                        </div>

                                          <div class="form-group">
                                            <label>Category Name</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ $collection->category->category_name }}" disabled>
                                        </div>

                                        <div class="form-group">
                                            <label>Status</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ $collection->status }}" disabled>
                                        </div>

                                        <div class="form-group">
                                            <label>Price</label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ number_format($collection->item_price, 2) }}" disabled>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>

                                    </div>

                            </div>
                        </div>
                    </div>
               @endforeach
            </tbody>
        </table>
    </div>

</section>
@stop

@section('js')
<script>
  const defaultImage = "{{ asset('default_image/default_image.jpg') }}";
  const imageInput = document.getElementById('image');
  const preview = document.getElementById('preview');
  const removeBtn = document.getElementById('removeBtn');

  function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function() {
      preview.src = reader.result;
      removeBtn.classList.remove('d-none'); // show button
    };
    reader.readAsDataURL(file);
  }

  function removeImage() {
    preview.src = defaultImage;  // reset to default
    imageInput.value = '';       // clear file input
    removeBtn.classList.add('d-none'); // hide button
  }
</script>

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
});
</script>
@stop
