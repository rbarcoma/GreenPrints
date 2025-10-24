<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemModel extends Model
{
    use HasFactory;

    // protected $table = 'item';

    // protected $fillable = ['item_name', 'item_desc', 'item_category', 'item_price', 'status'];

    // public function category()
    // {
    //     return $this->belongsTo(ItemCategoryModel::class, 'item_category', 'id');
    // }


    // public function barcode()
    // {
    //     return $this->hasOne(ItemBarcode::class, 'item_id');
    // }

    // public function image()
    // {
    //    return $this->hasOne(ItemImage::class, 'item_id');
    // }
}
