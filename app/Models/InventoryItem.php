<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_category',
        'type',
        'quantity_unit',
        'total_stock',
        'reorder_level',
        'status',
    ];

    public function batches() {
        return $this->hasMany(InventoryBatch::class, 'inventory_item_id');
    }

    public function logs() {
        return $this->hasMany(InventoryLog::class, 'inventory_id');
    }
}
