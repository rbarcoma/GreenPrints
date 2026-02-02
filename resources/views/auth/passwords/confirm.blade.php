{{-- @extends('adminlte::auth.passwords.confirm') --}}

@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', 'GreenPrints')

@section('auth_body')
<p class="login-box-msg">
    Enter the 4-digit OTP sent to your email and your new password.
</p>

<form action="{{ route('password.confirm.post') }}" method="POST">
    @csrf

    <div class="input-group mb-3">
        <input type="text" name="otp" class="form-control" placeholder="4-digit OTP" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-key"></span>
            </div>
        </div>
    </div>

    <div class="input-group mb-3">
        <input type="password" name="password" class="form-control" placeholder="New Password" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
    </div>

    <div class="input-group mb-3">
        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password" required>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-block">
        Reset Password
    </button>
</form>

<p class="mt-3 text-center">
    <a href="{{ route('login') }}">← Back to Login</a>
</p>
@endsection
