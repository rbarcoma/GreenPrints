<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function userIndex()
    {
        $users = User::all();

        return view('admin.user', compact('users'));
    }

    public function userCreate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',

            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ],

            'role' => 'required|string',
            'status' => 'required|in:active,inactive'
        ], [

            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.regex' => 'Password must contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character (@$!%*#?&).',
        ]);

        // Store user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
        ]);

        return redirect()->route('menu.user')->with('success', 'User created successfully!');
    }

    public function changePassword(Request $request, $id)
    {

       $request->validate([
            'currentPassword' => 'required',

            'newPassword' => [
                'required',
                'min:8',
                'same:confirmPassword',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ],
        ], [

            'newPassword.min' => 'New password must be at least 8 characters.',
            'newPassword.same' => 'New password confirmation does not match.',
            'newPassword.regex' => 'New password must contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character (@$!%*#?&).',
        ]);

       $user = User::findOrFail($id);

       if (!Hash::check($request->currentPassword, $user->password)) {
          return back()->with('error', 'Current password is incorrect.');
       }

       $user->password = Hash::make($request->newPassword);
       $user->save();

       return redirect()->route('menu.user')->with('success', 'Password changed successfully!');
    }

    public function userUpdate(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role'  => 'required|string',
            'status'=> 'required|string'
        ]);

        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->status = $request->status;
        $user->save();

        return redirect()->route('menu.user')->with('success', 'User updated successfully!');
    }

    public function userDelete(Request $request, $id)
    {
        $request->validate([
            'password' => ['required']
        ]);

        $loggedUser = auth()->user();

        if (!Hash::check($request->password, $loggedUser->password)) {
            return back()->with('error', 'Incorrect password! Cannot delete user.');
        }

        if ($loggedUser->id == $id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        User::findOrFail($id)->delete();

        return redirect()->route('menu.user')->with('success', 'User deleted successfully!');
    }

}


