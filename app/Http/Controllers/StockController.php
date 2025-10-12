<?php

namespace App\Http\Controllers;

use App\Models\ItemModel;
use App\Models\StockModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
   public function index()
   {
        $items = ItemModel::all();

        $stocks = StockModel::with('item')->get();
        
        $inventory = $stocks->groupBy('item_id')->map(function ($group) {
        $total = 0;
        foreach ($group as $stock) {
                $total += $stock->type === 'Stock In'
                    ? $stock->quantity
                    : -$stock->quantity;
            }

            return [
                'item' => $group->first()->item,
                'total_quantity' => $total,
            ];
        });


        $stockIn = StockModel::with('item')->where('type','Stock In')->get();
        $stockOut = StockModel::with('item')->where('type','Stock Out')->get();

        return view('process-automation.stocks.index', compact('items','inventory','stockIn','stockOut'));
   }

   public function StockIn(Request $request)
   {    
        $user = Auth::user();
        $user->id;

        $validated = $request->validate([
            'item' => 'required|string',
            'quantity' => 'required|numeric',
            'remarks' => 'nullable'
        ]);

        StockModel::create([
            'item_id' => $validated['item'],
            'type' => $request->type,
            'quantity'=> $validated['quantity'],
            'remarks' => $validated['remarks'],
            'user_id' => $user->id,
        ]);
        
        return  redirect()->route('stock.index');

   }



}
