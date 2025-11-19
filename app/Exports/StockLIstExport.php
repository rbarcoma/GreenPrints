<?php

namespace App\Exports;

use App\Models\StockModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StockListExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $stocks = StockModel::with('item')->get();

        $inventory = $stocks->groupBy('item_id')->map(function ($group) {
            $total = 0;
            foreach ($group as $s) {
                $total += $s->type === 'Stock In' ? $s->quantity : -$s->quantity;
            }
            return [
                'item' => $group->first()->item,
                'total_quantity' => $total,
            ];
        });

        return view('process-automation.stocks.excel.stock-list', [
            'inventory' => $inventory
        ]);
    }
}
