{{-- @extends('adminlte::auth.passwords.email') --}}

@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', 'GreenPrints')

@section('auth_body')
<p class="login-box-msg">
    Enter your registered email address and we will send you a 4-digit OTP code to reset your password.
</p>

<form action="{{ route('password.sendOtp') }}" method="POST">
    @csrf

    <div class="input-group mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-envelope"></span>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-block">
        Send OTP
    </button>
</form>

<p class="mt-3 text-center">
    <a href="{{ route('login') }}">← Back to Login</a>
</p>
@endsection
