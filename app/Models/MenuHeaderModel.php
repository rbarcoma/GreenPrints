<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MenuModel;

class MenuHeaderModel extends Model
{
    use HasFactory;

    protected $table = 'menu_headers';

    protected $fillable = ['name', 'menu_ids'];

    protected $casts = [
        'menu_ids' => 'array',
    ];


     public function getMenusAttribute()
    {
        return MenuModel::whereIn('id', $this->menu_ids)->get();
    }
}
