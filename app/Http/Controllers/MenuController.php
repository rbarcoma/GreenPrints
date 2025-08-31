<?php

namespace App\Http\Controllers;

use App\Models\MenuHeaderModel;
use App\Models\MenuModel;
use Illuminate\Http\Request;

class MenuController extends Controller
{
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
            'route'  => 'nullable|string'
        ]);

        MenuModel::create([
            'name'      => $validated['name'],
            'slug'      => $validated['slug'],
            'icon'      => $validated['icon'],
            'parent_id' => $validated['parent'],
            'route'     => $validated['route'],
        ]);

        return redirect()->route('menu');
    }



    // Menu Header part

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
        return redirect()->route('menu-header');
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
}
