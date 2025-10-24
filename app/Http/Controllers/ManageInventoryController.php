<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryItem;
use App\Models\InventoryBatch;
use App\Models\Supplier;
use App\Models\InventoryLog;

class ManageInventoryController extends Controller
{
    public function index()
    {
        $items = InventoryItem::with('batches')->get();
        $batches = InventoryBatch::with('item')->get();
        $suppliers = Supplier::all();
        $logs = InventoryLog::latest()->get();

        $totalValue = $batches->sum(fn($b) => $b->quantity * $b->unit_cost);
        $availableItems = $items->where('status', 'Available')->count();
        $lowStock = $items->filter(fn($i) => $i->total_stock > 0 && $i->total_stock <= $i->reorder_level)->count();
        $outStock = $items->where('total_stock', 0)->count();

        $mostUsed = InventoryBatch::with('item')
            ->selectRaw('inventory_item_id, SUM(quantity) as total_quantity')
            ->groupBy('inventory_item_id')
            ->orderByDesc('total_quantity')
            ->take(10)
            ->get();

        return view('inventory.index', compact(
            'items', 'batches', 'suppliers', 'logs',
            'totalValue', 'availableItems', 'lowStock', 'outStock', 'mostUsed'
        ));
    }

    // ADD INVENTORY ITEM

    public function addItem(Request $request)
    {
        $validated = $request->validate([
            'item_category' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'quantity_unit' => 'required|string|max:100',
            'total_stock' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:1',
        ]);

        if ($validated['total_stock'] == 0) {
            $validated['status'] = 'Out of Stock';
        } elseif ($validated['total_stock'] <= $validated['reorder_level']) {
            $validated['status'] = 'Low Stock';
        } else {
            $validated['status'] = 'Available';
        }

        InventoryItem::create($validated);

        return redirect()->back()->with('success', '✅ Item added successfully!');
    }

    // ADD BATCH ITEM

    public function addBatch(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:inventory_items,id',
            'title' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'obtained_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:obtained_date',
        ]);

        $batch = InventoryBatch::create([
            'inventory_item_id' => $validated['item_id'],
            'title' => $validated['title'],
            'quantity' => $validated['quantity'],
            'unit_cost' => $validated['unit_cost'],
            'supplier' => $validated['supplier'] ?? null,
            'obtained_date' => $validated['obtained_date'] ?? null,
            'expiry_date' => $validated['expiry_date'] ?? null,
        ]);

        $item = InventoryItem::find($validated['item_id']);
        $item->total_stock += $validated['quantity'];

        if ($item->total_stock == 0) {
            $item->status = 'Out of Stock';
        } elseif ($item->total_stock <= $item->reorder_level) {
            $item->status = 'Low Stock';
        } else {
            $item->status = 'Available';
        }

        $item->save();

        return redirect()->back()->with('success', '✅ Batch added and inventory updated successfully!');
    }


    // ADD SUPPLIER

    public function addSupplier(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
        ]);

        Supplier::create($validated);

        return redirect()->back()->with('success', '✅ Supplier added successfully!');
    }

    public function updateItem(Request $request, $id)
    {
        $validated = $request->validate([
            'item_category' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'quantity_unit' => 'required|string|max:100',
            'total_stock' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:1',
        ]);

        $item = InventoryItem::findOrFail($id);

        if ($validated['total_stock'] == 0) {
            $validated['status'] = 'Out of Stock';
        } elseif ($validated['total_stock'] <= $validated['reorder_level']) {
            $validated['status'] = 'Low Stock';
        } else {
            $validated['status'] = 'Available';
        }

        $item->update($validated);

        return redirect()->back()->with('success', '✅ Item updated successfully!');
    }

    public function deleteItem($id)
    {
        $item = InventoryItem::findOrFail($id);
        $item->delete();

        return redirect()->back()->with('success', '🗑️ Item deleted successfully!');
    }

    public function updateBatch(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'obtained_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:obtained_date',
        ]);

        $batch = InventoryBatch::findOrFail($id);
        $item = InventoryItem::find($batch->inventory_item_id);

        // Adjust total stock (difference between old and new quantity)
        $oldQty = $batch->quantity;
        $newQty = $validated['quantity'];
        $diff = $newQty - $oldQty;

        $item->total_stock += $diff;

        $batch->update($validated);

        // Auto-update item status
        if ($item->total_stock == 0) {
            $item->status = 'Out of Stock';
        } elseif ($item->total_stock <= $item->reorder_level) {
            $item->status = 'Low Stock';
        } else {
            $item->status = 'Available';
        }

        $item->save();

        return redirect()->back()->with('success', '✅ Batch and inventory updated successfully!');
    }

    public function deleteBatch($id)
    {
        $batch = InventoryBatch::findOrFail($id);
        $item = InventoryItem::find($batch->inventory_item_id);

        // Subtract quantity from item total stock
        $item->total_stock -= $batch->quantity;
        if ($item->total_stock < 0) {
            $item->total_stock = 0;
        }

        // Auto-update item status
        if ($item->total_stock == 0) {
            $item->status = 'Out of Stock';
        } elseif ($item->total_stock <= $item->reorder_level) {
            $item->status = 'Low Stock';
        } else {
            $item->status = 'Available';
        }

        $item->save();

        $batch->delete();

        return redirect()->back()->with('success', '🗑️ Batch deleted and inventory updated successfully!');
    }

}
