<?php

namespace App\Http\Controllers;

use App\Models\MenuModel;
use App\Models\RoleModel;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function roleIndex()
    {

        $menus = MenuModel::all();

        $roles = RoleModel::all();

        return view('admin.role', compact('roles', 'menus'));
    }


    public function roleCreation(Request $request)
    {

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string',
            'description' => 'nullable|string',
            'menus'       => 'array|nullable',
        ]);

        // dd($validated['menus']);

        RoleModel::create([
            'name'         => $validated['name'],
            'slug'         => $validated['slug'],
            'description'  => $validated['description'],
            'menu_ids'     => $validated['menus'] ?? [],
        ]);

        return redirect()->route('menu.role');
    }


    public function updateRole($id, Request $request)
    {

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string',
            'description' => 'nullable|string',
            'menus'       => 'array|nullable',
        ]);

        $role = RoleModel::findOrFail($id);

        $role->update([
            'name'         => $validated['name'],
            'slug'         => $validated['slug'],
            'description'  => $validated['description'],
            'menu_ids'     => $validated['menus'] ?? [],
        ]);

        return redirect()->route('menu.role');
    }
}
