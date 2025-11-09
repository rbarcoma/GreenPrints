@extends('adminlte::page')

@section('title', 'User')

@section('content_header')
    <h1>Admin User</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- toast message --}}

        @if(session('success'))

        <div class="alert aler-success">
            <strong class="mr-auto">Success</strong>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
             <div class="alert aler-success">
                <strong class="mr-auto">Error</strong>
                <span>{{ session('error') }}</span>
            </div>
        @endif



    <section class="border p-3 card">


        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Admin User List</h4>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                Create User
            </button>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-lg modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5" id="exampleModalLabel">User creation</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                     </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('createMenu') }}" method="POST">
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
                            <label for="formGroupExampleInput" class="form-label">Icon</label>
                            <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Please input icon"  name="icon">
                        </div>

                        <div class="mb-3">
                            <label for="formGroupExampleInput" class="form-label">Role</label>
                        </div>

                        <div class="mb-3">
                            <label for="formGroupExampleInput" class="form-label">Status</label>
                        </div>

                        <div class="mb-3">
                            <label for="formGroupExampleInput" class="form-label">Route</label>
                            <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Please input route" name="route">
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
        <table id="userTable" class="table   table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>Role</td>
                    <td></td>
                    <td>
                        <button type="button" class="btn btn-primary">Edit</button>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#changePassword{{ $user->id }}" >
                            Change Password
                        </button>
                    </td>
                </tr>


                {{-- change Password Modal part start --}}
                <div class="modal fade" id="changePassword{{ $user->id }}" tabindex="-1" aria-labelledby="changePassword" aria-hidden="true">
                    <div class="modal-dialog  modal-lg modal-dialog-centered">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fs-5" id="exampleModalLabel">Change Password for: {{ $user->email }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('password.change', $user->id) }}" method="POST">
                                @csrf
                            <div class="mb-3">
                                <label for="formGroupExampleInput" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="formGroupExampleInput" placeholder="Please input current password" required name="currentPassword">
                            </div>

                             <div class="mb-3">
                                <label for="formGroupExampleInput" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="formGroupExampleInput" placeholder="Please input new password" required name="newPassword">
                            </div>

                            <div class="mb-3">
                                <label for="formGroupExampleInput" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="formGroupExampleInput" placeholder="Confirm new password" required name="confirmPassword">
                            </div>
                             
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit changes</button>
                        </div>
                            </form>
                    </div>
                </div>

                {{-- change Password Modal part end --}}
                
                @endforeach

            </tbody>

        </table>
    </section>

@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('#toastSuccess').toast('show');
            $('#toastError').toast('show');
        });
    </script>
    <script>
        $(function(){
            $('#userTable').DataTable({
                responsive:true,
                autowidth:true,
                pageLength:10,
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                }
            })
        })
    </script>
@stop
