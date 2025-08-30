<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuModel extends Model
{
    use HasFactory;

    protected $table = 'menus';

    protected $fillable = ['name', 'icon', 'slug', 'parent_id', 'route'];


    public function headers()
    {
        return $this->belongsToMany(MenuHeaderModel::class, 'header_menus', 'menus.id', 'header_menus.id');
    }
}
