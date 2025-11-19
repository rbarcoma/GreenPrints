<?php

namespace App\Exports;

use App\Models\StockModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class StockInExport implements FromView, ShouldAutoSize
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

        $records = StockModel::with(['item', 'user'])
            ->where('type', 'Stock In')
            ->whereDate('date', '>=', $dateFrom)
            ->get();

        return view('process-automation.stocks.excel.stock-in', [
            'records' => $records
        ]);
    }
}
