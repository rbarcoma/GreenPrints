<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemImage extends Model
{
    use HasFactory;

    protected $table = 'item_image';

    protected $fillable = ['item_id', 'image_name', 'image_path'];
}
