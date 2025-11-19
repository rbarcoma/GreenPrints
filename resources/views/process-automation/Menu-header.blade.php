@extends('adminlte::page')

@section('title', 'Menu Header')

@section('content_header')
    <h1>Menu Header</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            @foreach ($errors->all() as $error)
                {{ $error }} <br>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true" class="text-light">&times;</span>
                </button>
            @endforeach
        </div>
    @endif

    {{-- toast message --}}

        {{-- @if(session('success'))
        <div class="alert aler-success">
            <strong class="mr-auto">Success</strong>
            <span>{{ session('success') }}</span>
        </div>
        @endif --}}

        {{-- @if(session('error'))
             <div class="alert aler-success">
                <strong class="mr-auto">Error</strong>
                <span>{{ session('error') }}</span>
            </div>
        @endif --}}

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

    <section class="border p-3 card">


        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Menu Header List</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Create Menu Header
            </button>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-lg modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Menu Header creation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('menu-header.create') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="formGroupExampleInput" class="form-label">Name</label>
                            <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Please inpute header name" required name="name">
                        </div>

                        <div class="mb-3">
                            <label for="formGroupExampleInput" class="form-label">Menus</label>
                            @foreach ($menu as $menus )
                             <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $menus->id }}" id="checkDefault{{ $menus->id }}" name="menus[]">
                                <label class="form-check-label" for="checkDefault">
                                    {{ $menus->name }}
                                </label>
                            </div>
                            @endforeach

                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Add Menu Header</button>
                </div>
                  </form>
                </div>
            </div>
        </div>

        <table id="example" class="table   table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Menus</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $menuHeader as $headers)
                    <tr>
                        <td>{{ $headers->id }}</td>
                        <td>{{ $headers->name }}</td>
                        <td>
                            <ul>
                            @foreach ($headers->menus as $menu)
                                <li>{{ $menu->name }}</li>
                            @endforeach
                            </ul>
                        </td>
                        <td>
                            <div class="d-flex">
                                <button type="button" class="btn btn-sm btn-primary mr-2" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $headers->id }}">
                                    Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteHeader{{ $headers->id }}">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- EDIT MODAL -->
                    <div class="modal fade" id="exampleModal{{ $headers->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog  modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Menu Header: {{ $headers->name }} </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('menu-header.update', $headers->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-3">
                                            <label for="formGroupExampleInput" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Please inpute header name" required name="name" value="{{ $headers->name }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="formGroupExampleInput" class="form-label">Menus</label>
                                            @foreach ($menuItem as $menuItems)
                                                <div class="form-check">
                                                    <input class="form-check-input"
                                                        type="checkbox"
                                                        value="{{ $menuItems->id }}"
                                                        id="checkDefault{{ $headers->id }}-{{ $menuItems->id }}"
                                                        name="menus[]"
                                                        @if(is_array($headers->menu_ids) && in_array($menuItems->id, $headers->menu_ids)) checked @endif>

                                                    <label class="form-check-label" for="checkDefault{{ $headers->id }}-{{ $menuItems->id }}">
                                                        {{ $menuItems->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success">Save changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DELETE USER MODAL -->
                    <div class="modal fade" id="deleteHeader{{ $headers->id }}" tabindex="-1" aria-labelledby="deleteHeaderLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">

                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">Delete Menu Header: {{ $headers->name }}</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                </div>

                                <div class="modal-body">
                                    <p class="mb-3 text-center">
                                        ⚠️ This action cannot be undone.<br>
                                        To continue, enter your <strong>current password</strong>.
                                    </p>
                                    <form action="{{ route('menu-header.delete', $headers->id) }}" method="POST">
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

{{-- @section('footer')
    <div class="float-right">
        Version: {{ config('app.version', '1.0.0') }}
    </div>

    <strong>
        <a href="{{ config('app.company_url', '#') }}">
            {{ config('app.company_name', 'My company') }}
        </a>
    </strong>
@stop --}}


@section('css')
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
@stop

@section('js')
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>

    {{-- Initialize DataTable --}}
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>

    <script>console.log("✅ DataTables is now working with AdminLTE!");</script>
@stop
