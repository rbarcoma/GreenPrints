@extends('adminlte::page')

@section('title', 'Menu')

@section('content_header')
    <h1>Menu</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>

     @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true" class="text-light">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true" class="text-light">&times;</span>
            </button>
        </div>
    @endif


    {{-- toast message --}}
{{-- <div class="toast-container position-fixed bottom-0 end-0 p-3">
    @if(session('success'))
        <div id="liveToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div id="liveToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif
</div> --}}


    <section class="border p-3 card">


        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Menu List</h4>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                Create Menu
            </button>
        </div>

        <!-- Modal -->
         <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
           <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Menu creation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('createMenu') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="formGroupExampleInput" class="form-label">Name</label>
                            <input type="text" class="form-control" id="menuName" placeholder="Please input name" required name="name">
                        </div>

                         <div class="mb-3">
                            <label for="formGroupExampleInput" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="menuSlug" placeholder="Please input slug" name="slug">
                        </div>

                        <div class="mb-3">
                            <label for="formGroupExampleInput" class="form-label">Icon</label>
                            <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Please input icon"  name="icon">
                        </div>

                        <div class="mb-3">
                                <label for="formGroupExampleInput" class="form-group">Parent</label>
                              <select class="custom-select" id="inputGroupSelect01" name="parent">
                                <option selected value="">None</option>
                                  @foreach ($menu as $menus )
                                    <option value="{{ $menus->id }}">{{ $menus->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="mb-3">
                            <label for="formGroupExampleInput" class="form-label">Route</label>
                            <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Please input route" name="route">
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Add Menu</button>
                </div>
                  </form>p
                </div>
            </div>
        </div>

        <table id="menuTable" class="table   table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Icon</th>
                    <th>Parent</th>
                    <th>Route</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $menu as $menus)
                <tr>
                    <td>{{ $menus->id }}</td>
                    <td>{{ $menus->name }}</td>
                    <td>{{ $menus->slug }}</td>
                    <td>{{ $menus->icon }}</td>
                    <td>{{ $menus->parent ? $menus->parent->name : '' }}</td>
                    <td>{{ $menus->route }}</td>

                    <td>
                        <div class="d-flex">
                            <button type="button" class="btn btn-sm btn-primary mr-2" data-toggle="modal" data-target="#exampleModal{{ $menus->id }}">
                                Edit
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteMenu{{ $menus->id }}">
                                Delete
                            </button>
                        </div>
                    </td>
                </tr>


                {{-- EDIT MODAL --}}
        <div class="modal fade" id="exampleModal{{ $menus->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-lg modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="exampleModalLabel">Edit Menu: {{ $menus->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('menu.update',$menus->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="formGroupExampleInput" class="form-label">Name</label>
                        <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Please input name" required name="name" value="{{ $menus->name }}">
                    </div>

                        <div class="mb-3">
                        <label for="formGroupExampleInput" class="form-label">Slug</label>
                        <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Please input slug" name="slug" value="{{ $menus->slug }}">
                    </div>

                    <div class="mb-3">
                        <label for="formGroupExampleInput" class="form-label">Icon</label>
                        <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Please input icon"  name="icon" value="{{ $menus->icon }}">
                    </div>

                    <div class="mb-3">
                        <label for="formGroupExampleInput" class="form-label">Parent</label>

                        <select class="form-select form-select-sm mb-3" aria-label="Large select example" name="parent">

                            <option selected value="">None</option>
                            @foreach ($menu as $m )
                            <option value="{{ $m->id }}">{{ $m->name }}</option>
                                @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="formGroupExampleInput" class="form-label">Route</label>
                        <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Please input route" name="route" value="{{ $menus->route }}">
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

        <!-- DELETE USER MODAL -->
        <div class="modal fade" id="deleteMenu{{ $menus->id }}" tabindex="-1" aria-labelledby="deleteMenuLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Delete Menu: {{ $menus->name }}</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <p class="mb-3 text-center">
                            ⚠️ This action cannot be undone.<br>
                            To continue, enter your <strong>current password</strong>.
                        </p>
                        <form action="{{ route('menu.delete', $menus->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <div class="form-group">
                                <label>Enter your password</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                            </div>

                            <button type="submit" class="btn btn-danger btn-block">Confirm Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

                @endforeach

            </tbody>

        </table>
    </section>

@stop


@section('js')

    {{-- name & slug auto --}}
   <script>
        const menuName = document.getElementById('menuName');
        const menuSlug = document.getElementById('menuSlug');
        menuName.addEventListener('keyup', function() {
                let slug = menuName.value
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');

                menuSlug.value = slug;
         });

   </script>

    <script>
      $(function () {
        $('#menuTable').DataTable({
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
