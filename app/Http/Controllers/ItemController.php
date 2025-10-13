<?php

namespace App\Http\Controllers;

use App\Models\ItemBarcode;
use App\Models\ItemCategoryModel;
use App\Models\ItemImage;
use App\Models\ItemModel;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;


class ItemController extends Controller
{
    public function index()
    {

        $category = ItemCategoryModel::all();
        $item_collection = ItemModel::all();

       return view('process-automation.item.Item', compact('category','item_collection'));
    }


    public function createItem(Request $request)
    {
        // dd($request);
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'category' => 'required',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $item = ItemModel::firstOrCreate([
            'item_name' => $validated['name'],
            'item_desc' => $validated['description'],
            'item_category' => $validated['category'],
            'item_price' => $validated['price'],
            'status' => $validated['status'],
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $folderPath = 'item/images';
            $fullpath = $folderPath . '/' . $fileName;

            $file->move(public_path($folderPath), $fileName);

            ItemImage::firstOrCreate([
                'item_id'    => $item->id,
                'image_name' => $fileName,
                'image_path' => $fullpath
            ]);
        }

        do {
            $barcodeValue = rand(100000000000, 999999999999);
        } while (ItemBarcode::where('barcode_value', $barcodeValue)->exists());
        ItemBarcode::firstOrCreate([
            'item_id' => $item->id,
            'barcode_value' => $barcodeValue
        ]);

        // dd($validated);
        return redirect()->route('item.index');
    }




}
