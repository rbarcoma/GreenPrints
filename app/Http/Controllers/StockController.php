<?php

namespace App\Http\Controllers;

use App\Models\ItemModel;
use App\Models\StockModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Exports\StockListExport;
use App\Exports\StockInExport;
use App\Exports\StockOutExport;
use Maatwebsite\Excel\Facades\Excel;


class StockController extends Controller
{
    public function index()
    {
        $items = ItemModel::where('status', 'active')->get();
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


        $availableForStockOut = collect($inventory)->filter(function ($data) {
            return $data['total_quantity'] > 0;
        });

        $stockIn = StockModel::with('item')->where('type', 'Stock In')->get();
        $stockOut = StockModel::with('item')->where('type', 'Stock Out')->get();

        return view('process-automation.stocks.index', compact(
            'items',
            'inventory',
            'stockIn',
            'stockOut',
            'availableForStockOut'
        ));
    }

    public function StockIn(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'item' => 'required|string',
            'quantity' => 'required|numeric|min:1',
            'remarks' => 'nullable'
        ]);

        $item = ItemModel::find($validated['item']);
        if (!$item || $item->status !== 'active') {
            return back()->with('error', 'Cannot add stock for inactive item.');
        }

        $currentStock = StockModel::where('item_id', $item->id)
            ->get()
            ->reduce(function ($total, $record) {
                return $total + ($record->type === 'Stock In' ? $record->quantity : -$record->quantity);
            }, 0);

        if ($request->type === 'Stock Out' && $validated['quantity'] > $currentStock) {
            return back()->with('error', 'Stock out quantity exceeds available stocks!');
        }

        StockModel::create([
            'item_id'  => $validated['item'],
            'type'     => $request->type,
            'quantity' => $validated['quantity'],
            'remarks'  => $validated['remarks'],
            'user_id'  => $user->id,
            'date'     => $request->input('date') ?? Carbon::now()->toDateString(),
        ]);

        return redirect()->route('stock.index')->with('success', 'Stock added successfully!');
    }

    public function ScannerInsertion(Request $request)
    {


        $user = Auth::user();
        $items = $request->items; // array of items

        foreach ($items as $item) {

            $itemId = $item['item_id'];
            $quantity = $item['qty'];
            $type = $item['type'];
            $remarks = $item['remarks'] ?? null;
            $date = $item['date'] ?? now()->toDateString();

            $itemModel = ItemModel::find($itemId);

            if (!$itemModel || $itemModel->status !== 'active') {
                continue;
            }

            if ($type === 'Stock Out') {

                $currentStock = StockModel::where('item_id', $itemId)
                    ->get()
                    ->reduce(function ($total, $record) {
                        return $total + ($record->type === 'Stock In' ? $record->quantity : -$record->quantity);
                    }, 0);

                if ($quantity > $currentStock) {
                    continue;
                }
            }

            StockModel::create([
                'item_id'  => $itemId,
                'type'     => $type,
                'quantity' => $quantity,
                'remarks'  => $remarks,
                'user_id'  => $user->id,
                'date'     => $date,
            ]);
        }

        return redirect()->route('stock.index')->with('success', 'Stock added successfully!');
    }


    // stock list pdf
    public function exportStockListPDF(Request $request)
    {
        $filter = $request->get('filter', 'weekly');

        if ($filter === 'weekly') {
            $dateFrom = Carbon::now()->startOfWeek();
        } elseif ($filter === 'monthly') {
            $dateFrom = Carbon::now()->startOfMonth();
        } else {
            $dateFrom = Carbon::now()->startOfYear();
        }

        $stocks = StockModel::with('item')
            ->whereDate('date', '>=', $dateFrom)
            ->get();

        $inventory = $stocks->groupBy('item_id')->map(function ($group) {
            $total = 0;
            foreach ($group as $s) {
                $total += $s->type === 'Stock In' ? $s->quantity : -$s->quantity;
            }

            return [
                'item' => $group->first()->item,
                'total_quantity' => $total,
                'date' => $group->sortByDesc('date')->first()->date,
            ];
        });

        return \PDF::loadView('process-automation.stocks.pdf.stock-report-list', [
            'inventory' => $inventory,
            'filter'    => $filter
        ])->download("StockList-$filter.pdf");
    }

    // stock in records pdf
    public function exportStockInPDF(Request $request)
    {
        $filter = $request->get('filter', 'weekly');

        if ($filter === 'weekly') {
            $dateFrom = Carbon::now()->startOfWeek();
        } elseif ($filter === 'monthly') {
            $dateFrom = Carbon::now()->startOfMonth();
        } else {
            $dateFrom = Carbon::now()->startOfYear();
        }

        $stockIn = StockModel::with(['item', 'user'])
            ->where('type', 'Stock In')
            ->whereDate('date', '>=', $dateFrom)
            ->get();

        return \PDF::loadView('process-automation.stocks.pdf.stock-report-in', [
            'records' => $stockIn,
            'filter'  => $filter
        ])->download("StockIn-$filter.pdf");
    }

    // stock out records pdf
    public function exportStockOutPDF(Request $request)
    {
        $filter = $request->get('filter', 'weekly');

        if ($filter === 'weekly') {
            $dateFrom = Carbon::now()->startOfWeek();
        } elseif ($filter === 'monthly') {
            $dateFrom = Carbon::now()->startOfMonth();
        } else {
            $dateFrom = Carbon::now()->startOfYear();
        }

        $stockOut = StockModel::with(['item', 'user'])
            ->where('type', 'Stock Out')
            ->whereDate('date', '>=', $dateFrom)
            ->get();

        return \PDF::loadView('process-automation.stocks.pdf.stock-report-out', [
            'records' => $stockOut,
            'filter'  => $filter
        ])->download("StockOut-$filter.pdf");
    }


    //Stock list excel
    public function exportStockListExcel(Request $request)
    {
        $filter = $request->filter ?? 'weekly';
        return Excel::download(new StockListExport($filter), "StockList-$filter.xlsx");
    }

    //stock in excel
    public function exportStockInExcel(Request $request)
    {
        $filter = $request->filter ?? 'weekly';
        return Excel::download(new StockInExport($filter), "StockIn-$filter.xlsx");
    }

    //stock out excel
    public function exportStockOutExcel(Request $request)
    {
        $filter = $request->filter ?? 'weekly';
        return Excel::download(new StockOutExport($filter), "StockOut-$filter.xlsx");
    }
}
