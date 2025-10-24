<?php

namespace App\Http\Controllers;

use App\Models\ItemCategoryModel;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class ItemCategoryController extends Controller
{
    // public function index()
    // {
    //     $categories = ItemCategoryModel::all();


    //     return view('process-automation.item_category.index', compact('categories'));
    // }

    // public function create_category(Request $request)
    // {
    //     // dd($request);
    //     $validated = $request->validate([
    //         'name' => 'required|max:100',
    //         'item_desc' => 'nullable|string|max:255',
    //     ]);

    //     $item = ItemCategoryModel::firstOrCreate([
    //         'category_name' => $validated['name'],
    //         'category_desc' => $validated['item_desc'],
    //     ]);

    //     return redirect()->route('item_category.index');
    // }

    // public function update_category(Request $request, $id)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|max:100',
    //         'item_desc' => 'nullable|string|max:255',
    //     ]);

    //     $itemCategory = ItemCategoryModel::findOrFail($id);

    //     $itemCategory->category_name = $validated['name'];
    //     $itemCategory->category_desc = $validated['item_desc'];
    //     $itemCategory->update();

    //     return redirect()->route('item_category.index');

    // }

    // public function destroy($id)
    // {
    //     $itemCategory = ItemCategoryModel::findOrFail($id);
    //     $itemCategory->delete();

    //     return redirect()->route('item_category.index');
    // }
}
