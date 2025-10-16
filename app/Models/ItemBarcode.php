<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemBarcode extends Model
{
    use HasFactory;


    protected $table = 'item_barcode';

    protected $fillable = ['item_id', 'barcode_value'];

    public function item()
    {
        return $this->belongsTo(ItemModel::class, 'item_id');
    }
}
