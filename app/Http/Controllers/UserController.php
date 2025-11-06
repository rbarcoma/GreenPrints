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

    public function changePassword(Request $request, $id)
    {
       
       $request->validate([
          'currentPassword' => 'required',
          'newPassword'      => 'required|min:8|same:confirmPassword'
       ]);

       $user = User::findOrFail($id);

       if (!Hash::check($request->currentPassword, $user->password)) {
          return back()->with('error', 'Current password is incorrect.');
       }

       $user->password = Hash::make($request->newPassword);
       $user->save();


       return redirect()->route('menu.user')->with('success', 'Password changed successfully!');

    }
}
