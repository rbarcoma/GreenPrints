@extends('adminlte::page')

@section('title', 'Role')

@section('content_header')
    <h1>Role</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>


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
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Create Role
            </button>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-lg modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Menu creation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
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
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $role->id }}">
                         Edit
                        </button>
                    </td>
                </tr>


                {{-- EDIT MODAL --}}

                 <div class="modal fade" id="exampleModal{{ $role->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-lg modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Role: {{ $role->name }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                  </form>
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
