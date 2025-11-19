@extends('adminlte::page')

@section('title', 'Role')

@section('content_header')
    <h1>Role</h1>
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
            <h4 class="mb-0">Role List</h4>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                Create Role
            </button>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"  aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-lg modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Role creation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('menu.role-creation') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="formGroupExampleInput" class="form-label">Name</label>
                            <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Please input name" required name="name">
                        </div>

                         <div class="mb-3">
                            <label for="formGroupExampleInput" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Please input slug" name="slug">
                        </div>

                        <div class="mb-3">
                            <label for="formGroupExampleInput" class="form-label">Description</label>

                                <textarea class="form-control" placeholder="Leave a description here" id="floatingTextarea" name="description"></textarea>

                        </div>


                     <div class="mb-3">
                            <label for="formGroupExampleInput" class="form-label">Menus</label>
                            @foreach ($menus as $menu )
                             <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $menu->id }}" id="checkDefault{{ $menu->id }}" name="menus[]">
                                <label class="form-check-label" for="checkDefault">
                                    {{ $menu->name }}
                                </label>
                            </div>
                            @endforeach

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

        <table id="roleTable" class="table   table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Description</th>
                    <th>menus</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->slug }}</td>
                    <td>{{ $role->description }}</td>
                    <td>
                        <ul>
                        @foreach ($role->menus as $menu)
                                <li>{{ $menu->name }}</li>
                        @endforeach
                        </ul>
                    </td>

                    <td>
                        <div class="d-flex">
                            <button type="button" class="btn btn-sm btn-primary mr-2" data-toggle="modal" data-target="#exampleModal{{ $role->id }}">Edit</button>
                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteRole{{ $role->id }}">
                                Delete
                            </button>

                        </div>
                    </td>
                </tr>
                {{-- EDIT MODAL --}}
                <div class="modal fade" id="exampleModal{{ $role->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog  modal-lg modal-dialog-centered">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fs-5" id="exampleModalLabel">Edit Role: {{ $role->name }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('menu.role-update',$role->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="formGroupExampleInput" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Please input name" required name="name" value="{{ $role->name }}">
                                </div>

                                <div class="mb-3">
                                    <label for="formGroupExampleInput" class="form-label">Slug</label>
                                    <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Please input slug" name="slug" value="{{ $role->slug }}">
                                </div>

                                <div class="mb-3">
                                    <label for="formGroupExampleInput" class="form-label">Description</label>

                                        <textarea class="form-control" placeholder="Leave a description here" id="floatingTextarea" name="description"  >{{ $role->description }}</textarea>

                                </div>


                            <div class="mb-3">
                                        @foreach ($menus as $menuItems)
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                type="checkbox"
                                                value="{{ $menuItems->id }}"
                                                id="checkDefault{{ $role->id }}-{{ $menuItems->id }}"
                                                name="menus[]"
                                                @if(is_array($role->menu_ids) && in_array($menuItems->id, $role->menu_ids)) checked @endif>

                                            <label class="form-check-label" for="checkDefault{{ $role->id }}-{{ $menuItems->id }}">
                                                {{ $menuItems->name }}
                                            </label>
                                        </div>
                                    @endforeach
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
                <!-- DELETE ROLE MODAL -->
                <div class="modal fade" id="deleteRole{{ $role->id }}" tabindex="-1" aria-labelledby="deleteRoleLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Delete Role: {{ $role->name }}</h5>
                                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                            </div>

                            <div class="modal-body">
                                <p class="mb-3 text-center">
                                    ⚠️ This action cannot be undone.<br>
                                    To continue, enter your <strong>current password</strong>.
                                </p>

                                <form action="{{ route('menu.role.destroy', $role->id) }}" method="POST">
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
    <script>
        $(function () {
            $('#roleTable').DataTable({
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
