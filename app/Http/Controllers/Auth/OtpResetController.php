<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class OtpResetController extends Controller
{
    public function showEmailForm()
    {
        return view('auth.passwords.email');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $otp = rand(1000, 9999);

        Session::put('password_reset_otp', $otp);
        Session::put('password_reset_email', $request->email);

        // Send OTP via email
        Mail::raw("Your OTP Code: $otp", function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Password Reset OTP - GreenPrints');
        });

        return redirect()->route('password.confirm')->with('success', 'OTP sent to your email.');
    }

    public function showConfirmForm()
    {
        return view('auth.passwords.confirm');
    }

    public function confirmReset(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:4',
            'password' => 'required|confirmed|min:8'
        ]);

        if ($request->otp != Session::get('password_reset_otp')) {
            return back()->withErrors(['otp' => 'Invalid OTP code']);
        }

        $user = User::where('email', Session::get('password_reset_email'))->firstOrFail();
        $user->password = Hash::make($request->password);
        $user->save();

        Session::forget(['password_reset_otp', 'password_reset_email']);

        return redirect('/login')->with('success', 'Password reset successful! You can now login.');
    }
}
