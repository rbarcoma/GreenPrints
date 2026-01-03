<?php

namespace App\Http\Controllers;

use App\Models\MenuHeaderModel;
use App\Models\MenuModel;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class MenuController extends Controller
{

    // Menu PART start
    public function index()
    {
        $menus = MenuModel::all();

        return view('process-automation.Menu', ['menu' => $menus]);
    }


    public function createMenu(Request $request)
    {
        // dd($request);

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'slug'   => 'nullable|string',
            'icon'   => 'nullable|string',
            'parent' => 'nullable|integer',

        ]);

        MenuModel::create([
            'name'      => $validated['name'],
            'slug'      => $validated['slug'],
            'icon'      => $validated['icon'],
            'parent_id' => $validated['parent'],
            'route'     => $request->route,
        ]);

        return redirect()->route('menu')->with('success', 'User added successfully!');
    }

    public function updateMenu($id, Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'slug'   => 'nullable|string',
            'icon'   => 'nullable|string',
            'parent' => 'nullable|integer',
            'route'  => 'nullable|string'
        ]);


        $menu = MenuModel::findOrFail($id);

        $menu->update([
            'name'      => $validated['name'],
            'slug'      => $validated['slug'],
            'icon'      => $validated['icon'],
            'parent_id' => $validated['parent'],
            'route'     => $validated['route'],
        ]);

        return redirect()->route('menu')->with('success', 'User updated successfully!');
    }

    public function MenuDelete(Request $request, $id)
    {
        $request->validate([
            'password' => ['required']
        ]);

        $loggedUser = auth()->user();

        if (!Hash::check($request->password, $loggedUser->password)) {
            return back()->with('error', 'Incorrect password! Cannot delete user.');
        }

        MenuModel::findOrFail($id)->delete();

        return redirect()->route('menu')->with('success', 'User deleted successfully!');
    }

    // Menu Header part start

    public function menuHeaderIndex()
    {
        $menus = MenuModel::all();

        $menuHeaders = MenuHeaderModel::all();

        foreach ($menuHeaders as $header) {
            $header->menu_list = MenuModel::whereIn('id', $header->menu_ids)->get();
        }

        $menuItem  = MenuModel::all();

        return view('process-automation.Menu-header', ['menu' => $menus, 'menuHeader' => $menuHeaders, 'menuItem' => $menuItem]);
    }

    public function menuHeaderCreate(Request $request)
    {
        // dd($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'menus' => 'array|nullable',
        ]);

        MenuHeaderModel::create([
            'name' => strtoupper($validated['name']),
            'menu_ids' => $validated['menus'] ?? [],
        ]);
        return redirect()->route('menu-header')->with('success', 'Menu Header added successfully!');
    }

    public function menuHeaderUpdate($id, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'menus' => 'array|nullable',
        ]);

        $menuHeaders = MenuHeaderModel::findOrFail($id);

        $menuHeaders->update([
            'name' => strtoupper($validated['name']),
            'menu_ids' => $validated['menus'] ?? [],
        ]);

        return redirect()->route('menu-header')->with('success', 'Menu Header updated successfully!');
    }

    public function MenuHeaderDelete(Request $request, $id)
    {
        $request->validate([
            'password' => ['required']
        ]);

        $loggedUser = auth()->user();

        if (!Hash::check($request->password, $loggedUser->password)) {
            return back()->with('error', 'Incorrect password! Cannot delete user.');
        }

        MenuHeaderModel::findOrFail($id)->delete();

        return redirect()->route('menu-header')->with('success', 'User deleted successfully!');
    }

}
