<?php

namespace App\Console\Commands;

use App\Models\StockModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Mail;
use App\Mail\LowStockAlert; 

class CheckLowStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-low-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks inventory and sends low stock emails';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $stocks = \App\Models\StockModel::with('item')->get();

        $inventory = $stocks->groupBy('item_id')->map(function ($group) {
            $total = 0;
            foreach ($group as $stock) {
                $total += $stock->type === 'Stock In' ? $stock->quantity : -$stock->quantity;
            }
            return [
                'item' => $group->first()->item,
                'total_quantity' => $total,
            ];
        });

        foreach ($inventory as $record) {
            if ($record['total_quantity'] <= 10) {
                Mail::to('user@example.com')->send(
                    new LowStockAlert($record['item']->item_name, $record['total_quantity'])
                );

                $this->info("Low stock email sent: {$record['item']->item_name} ({$record['total_quantity']})");
            }
        }
    }
}
