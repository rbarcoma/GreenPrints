<?php

namespace App\Exports;

use App\Models\StockModel;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class StockExport implements FromView
{
    protected $filter;

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function view(): View
    {
        if ($this->filter === 'weekly') {
            $dateFrom = Carbon::now()->startOfWeek();
        } elseif ($this->filter === 'monthly') {
            $dateFrom = Carbon::now()->startOfMonth();
        } else {
            $dateFrom = Carbon::now()->startOfYear();
        }

        $stocks = StockModel::with('item')
            ->whereDate('date', '>=', $dateFrom)
            ->get();

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

        return view('reports.stock-excel', [
            'inventory' => $inventory,
            'stockIn' => $stocks->where('type', 'Stock In'),
            'stockOut' => $stocks->where('type', 'Stock Out'),
            'filter' => $this->filter
        ]);
    }
}
