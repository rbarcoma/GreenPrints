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

        return view('process-automation.item.Item', compact('category', 'item_collection'));
    }


    public function createItem(Request $request)
    {
        // dd($request);
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'category' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
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


    public function getItem($barcode)
    {
        $barcodeRecord = ItemBarcode::where('barcode_value', $barcode)
            ->with('item') // load related item
            ->first();

        if (!$barcodeRecord || !$barcodeRecord->item) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        // Kunin ang item data
        $item = $barcodeRecord->item;

        return response()->json([
            'item_id' => $item->id,
            'barcode' => $barcodeRecord->barcode_value,
            'item_name' => $item->item_name,
            'price' => $item->item_price,
        ]);
    }

    public function updateItem(Request $request, $id)
    {
        $item = ItemModel::findOrFail($id);

        $request->validate([
            'description' => 'required|string',
            'status' => 'required|in:active,inactive',
            'new_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $item->item_desc = $request->description;
        $item->status = $request->status;
        $item->save();

        if ($request->hasFile('new_image')) {

            // delete old image if exists
            if ($item->image && file_exists(public_path($item->image->image_path))) {
                unlink(public_path($item->image->image_path));
            }

            $file = $request->file('new_image');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $folderPath = 'item/images';
            $file->move(public_path($folderPath), $fileName);

            // update or create image record
            ItemImage::updateOrCreate(
                ['item_id' => $item->id],
                [
                    'image_name' => $fileName,
                    'image_path' => $folderPath . '/' . $fileName
                ]
            );
        }
        return redirect()->route('item.index')->with('success', 'Item updated successfully');
    }
}
