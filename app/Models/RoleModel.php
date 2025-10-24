<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    use HasFactory;


    // protected $table = 'role';

    // protected $fillable = ['name', 'slug', 'description', 'menu_ids'];

    // protected $casts = [
    //     'menu_ids' => 'array',
    // ];

    // public function getMenusAttribute()
    // {
    //     return MenuModel::whereIn('id', $this->menu_ids)->get();
    // }
}
