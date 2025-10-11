<?php

namespace App\Http\Controllers;

use App\Models\ItemCategoryModel;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;

class ItemCategoryController extends Controller
{
    public function index()
    {   
        $categories = ItemCategoryModel::all();
        

        return view('process-automation.item_category.index', compact('categories'));
    }

    public function create_category(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $item = ItemCategoryModel::firstOrCreate([
            'category_name' => $validated['name']
        ]);

        return redirect()->route('item_category.index');
    }
}
