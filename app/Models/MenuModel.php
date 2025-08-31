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
        return $this->belongsToMany(MenuHeaderModel::class, 'menu_headers', 'menus.id', 'menu_headers.id');
    }

    public function roles()
    {
        return $this->belongsToMany(RoleModel::class, 'role', 'menus.id', 'role.id');
    }
}
