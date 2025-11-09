@extends('adminlte::page')

@section('title', 'User')

@section('content_header')
    <h1>Admin User</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>

    {{-- @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}

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


    <section class="border p-3 card">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Admin User List</h4>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createUserModal">
                Create User
            </button>
        </div>

        <!-- Create User Modal -->
        <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('menu.user-create') }}" method="POST">
                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title" id="createUserModalLabel">Create User</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <div class="mt-2 p-2 border rounded bg-light" style="font-size: 15px;">
                                    <strong>Password Requirements:</strong>
                                    <ul class="list-unstyled mb-0 mt-2">
                                        <li id="req-length" class="text-success">✅ Minimum 8 characters</li>
                                        <li id="req-uppercase" class="text-success">✅ At least 1 uppercase letter</li>
                                        <li id="req-lowercase" class="text-success">✅ At least 1 lowercase letter</li>
                                        <li id="req-number" class="text-success">✅ At least 1 number</li>
                                        <li id="req-symbol" class="text-success">✅ At least 1 special character (@$!%*#?&)</li>
                                    </ul>
                                </div>
                            </div>
                          
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" placeholder="Enter name" name="name" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" placeholder="Enter email" name="email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" placeholder="Enter password" name="password" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" placeholder="Confirm password" name="password_confirmation" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-control" required>
                                    <option value="Admin">Admin</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Create User</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        {{-- TABLE --}}
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
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->status }}</td>
                    <td>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editUser{{ $user->id }}">
                            Edit
                        </button>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#changePassword{{ $user->id }}" >
                            Change Password
                        </button>
                    </td>
                </tr>

                <!-- Edit User Modal -->
                <div class="modal fade" id="editUser{{ $user->id }}" tabindex="-1" aria-labelledby="editUserLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title" id="editUserLabel">Edit User: {{ $user->name }}</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>

                            <form action="{{ route('menu.user-update', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="modal-body">
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Role</label>
                                        <select class="form-control" name="role" required>
                                            <option value="Admin" {{ $user->role == 'Admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select class="form-control" name="status" required>
                                            <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
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
                                <div class="mt-2 p-2 border rounded bg-light" style="font-size: 15px;">
                                    <strong>Password Requirements:</strong>
                                    <ul class="list-unstyled mb-0 mt-2">
                                        <li id="req-length" class="text-success">✅ Minimum 8 characters</li>
                                        <li id="req-uppercase" class="text-success">✅ At least 1 uppercase letter</li>
                                        <li id="req-lowercase" class="text-success">✅ At least 1 lowercase letter</li>
                                        <li id="req-number" class="text-success">✅ At least 1 number</li>
                                        <li id="req-symbol" class="text-success">✅ At least 1 special character (@$!%*#?&)</li>
                                    </ul>
                                </div>
                            </div>
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
