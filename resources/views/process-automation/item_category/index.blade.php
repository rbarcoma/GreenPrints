@extends('adminlte::page')

@section('title', 'Item Category')

@section('content_header')
    <h1>Item Category</h1>
@stop

@section('content')
<section class="border p-3 card">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Item List</h4>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">
            Create Category
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

                <form action="{{ route('item_category.create') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Item Category Title</label>
                            <input type="text" class="form-control" placeholder="Please input category title" required name="name">
                        </div>
                        <div class="mb-3">
                            <label for="formGroupExampleInput" class="form-group">Item Description</label>
                            <textarea class="form-control" placeholder="Leave a description here" id="floatingTextarea" name="item_desc"></textarea>
                            @error('item_desc') <div class="text-danger">{{ $message }}</div> @enderror
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
                    <th>Category Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->category_name }}</td>
                        <td>{{ $category->category_desc}}</td>
                        <td>
                            <div class="d-flex">
                                <button class="btn btn-sm btn-primary me-1 mr-2" data-toggle="modal" data-target="#editModal{{ $category->id }}">Edit</button>
                                <form action="{{ route('item_category.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure to delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1" role="dialog"
                         aria-labelledby="editModalLabel{{ $category->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Category</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span>&times;</span>
                                    </button>
                                </div>

                                <form action="{{ route('item_category.update', $category->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Category Name</label>
                                            <input type="text" name="name" class="form-control"
                                                   value="{{ $category->category_name }}" required>
                                        </div>
                                         <div class="mb-3">
                                            <label for="formGroupExampleInput" class="form-label">Description</label>
                                            <textarea class="form-control" placeholder="Leave a description here" id="floatingTextarea" name="item_desc"  >{{ $category->category_desc }}</textarea>
                                             @error('item_desc')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
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
