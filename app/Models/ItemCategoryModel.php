<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategoryModel extends Model
{
    use HasFactory;

    protected $table = 'item_category';

    protected $fillable = [
        'category_name',
        'item_desc',
    ];
}
