<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected function authenticated(Request $request, $user)
    {
        if ($user->status === 'inactive') {
            auth()->logout();

            return redirect()->back()->with('error', 'Your account is deactivated. Please contact admin.');
        }
    }

}
