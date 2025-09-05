@extends('adminlte::page')

@section('title', 'Menu')

@section('content_header')
    <h1>Menu</h1>
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
            <h4 class="mb-0">Menu List</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Create Menu
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
                            <label for="formGroupExampleInput" class="form-label">Parent</label>
                         
                            <select class="form-select form-select-sm mb-3" aria-label="Large select example" name="parent">
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
                    <td>{{ $menus->parent_id }}</td>
                    <td>{{ $menus->route }}</td>
                    <td>

                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $menus->id }}">
                        Edit
                    </button>
                    </td>
                </tr>


                {{-- EDIT MODAL --}}
        <div class="modal fade" id="exampleModal{{ $menus->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-lg modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Menu: {{ $menus->name }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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


@section('css')
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap5.min.css') }}">
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

@stop
