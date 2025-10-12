<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuModel;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $menus = [
            [
                'id' => 1,
                'icon' => 'fas fa-gauge',
                'name' => 'Dashboard',
                'slug' => 'dashboard',
                'parent_id' => null,
                'route' => '/home',
            ],
            [
                'id' => 2,
                'icon' => 'fas fa-cogs',
                'name' => 'Menu',
                'slug' => 'menu',
                'parent_id' => null,
                'route' => '/menu',
            ],
            [
                'id' => 3,
                'icon' => 'fas fa-pen',
                'name' => 'Menu Header',
                'slug' => 'header',
                'parent_id' => null,
                'route' => '/menu-header',
            ],
            [
                'id' => 4,
                'icon' => 'fas fa-users',
                'name' => 'User',
                'slug' => 'user',
                'parent_id' => null,
                'route' => '/user',
            ],
            [
                'id' => 5,
                'icon' => 'far fa-circle',
                'name' => 'Items',
                'slug' => 'item',
                'parent_id' => 7,
                'route' => '/items',
            ],
            [
                'id' => 6,
                'icon' => 'far fa-circle',
                'name' => 'Item Category',
                'slug' => 'item-category',
                'parent_id' => 7,
                'route' => '/item_category',
            ],
            [
                'id' => 7,
                'icon' => 'fas fa-box',
                'name' => 'Material',
                'slug' => 'products',
                'parent_id' => null,
                'route' => null,
            ],
            [
                'id' => 8,
                'icon' => null,
                'name' => 'Create Products',
                'slug' => 'create-products',
                'parent_id' => 7,
                'route' => '/products/create',
            ],
            [
                'id' => 9,
                'icon' => 'fas fa-user',
                'name' => 'Role',
                'slug' => 'role',
                'parent_id' => null,
                'route' => '/role',
            ],
            [
                'id' => 10,
                'icon' => 'fas fa-clipboard',
                'name' => 'Stocks',
                'slug' => 'stocks',
                'parent_id' => null,
                'route' => '/stock',
            ],
        ];

        foreach ($menus as $menu) {
            MenuModel::updateOrCreate(['id' => $menu['id']], $menu);
        }

    }
}
