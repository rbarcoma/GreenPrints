<?php

namespace App\Http\Controllers;

use App\Models\MenuModel;
use App\Models\RoleModel;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function roleIndex()
    {

        $roles = RoleModel::all();

        $menus = MenuModel::all();

        return view('admin.role', compact('roles', 'menus'));
    }


    public function roleCreation(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'slug'   => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        RoleModel::create([
            'name'         => $validated['name'],
            'slug'         => $validated['slug'],
            'description'  => $validated['description'],
        ]);

        return redirect()->route('menu.role');
    }
}
