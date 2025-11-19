<?php

namespace App\Http\Controllers;

use App\Models\ItemCategoryModel;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ItemCategoryController extends Controller
{
    public function index()
    {
        $categories = ItemCategoryModel::all();


        return view('process-automation.item_category.index', compact('categories'));
    }

    public function create_category(Request $request)
    {
        // dd($request);
        $validated = $request->validate([
            'name' => 'required|max:100',
            'item_desc' => 'nullable|string|max:255',
        ]);

        $item = ItemCategoryModel::firstOrCreate([
            'category_name' => $validated['name'],
            'category_desc' => $validated['item_desc'],
        ]);

        return redirect()->route('item_category.index');
    }

    public function update_category(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'item_desc' => 'nullable|string|max:255',
        ]);

        $itemCategory = ItemCategoryModel::findOrFail($id);

        $itemCategory->category_name = $validated['name'];
        $itemCategory->category_desc = $validated['item_desc'];
        $itemCategory->update();

        return redirect()->route('item_category.index');

    }

    public function destroy(Request $request, $id)
    {
        $request->validate([
            'password' => ['required']
        ]);

        $loggedUser = auth()->user();

        if (!Hash::check($request->password, $loggedUser->password)) {
            return back()->with('error', 'Incorrect password! Cannot delete category.');
        }

        ItemCategoryModel::findOrFail($id)->delete();

        return redirect()->route('item_category.index')->with('success', 'Category deleted successfully!');
    }


}
