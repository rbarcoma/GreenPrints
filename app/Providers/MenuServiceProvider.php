<?php

namespace App\Providers;

use App\Models\MenuHeaderModel;
use App\Models\MenuHeader;
use App\Models\MenuModel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider   
{
    public function boot(): void
    {
        if (!Schema::hasTable('menus')) {
            return;
        }

        $query = MenuModel::query();

        if (Schema::hasColumn('menus', 'sort')) {
            $query->orderBy('parent_id')->orderBy('sort')->orderBy('id');
        } else {
            $query->orderBy('parent_id')->orderBy('id');
        }

        $allMenus = $query->get();

        // --- build recursive for submenus ---
        $grouped = $allMenus->groupBy('parent_id');
        $build = function ($parentId) use (&$build, $grouped) {
            $children = $grouped->get($parentId, collect());

            return $children->map(function ($m) use (&$build) {
                $item = [
                    'text' => $m->name,
                    'url'  => $m->route ?: '#',
                    'icon' => $m->icon ?: 'far fa-circle',
                ];

                $children = $build($m->id);
                if ($children->isNotEmpty()) {
                    $item['submenu'] = $children->toArray();
                }

                return $item;
            });
        };

        // --- collect menus that are already inside headers ---
        $usedMenuIds = [];

        $dbMenu = [];
        if (Schema::hasTable('menu_headers')) {
            $menuHeaders = MenuHeaderModel::all();

            foreach ($menuHeaders as $header) {
                // Add header label
                $dbMenu[] = ['header' => $header->name];

                // Add menus under this header
                $menus = MenuModel::whereIn('id', $header->menu_ids)->get();
                foreach ($menus as $m) {
                    $usedMenuIds[] = $m->id;

                    $item = [
                        'text' => $m->name,
                        'url'  => $m->route ?: '#',
                        'icon' => $m->icon ?: 'far fa-circle',
                    ];

                    $children = $build($m->id);
                    if ($children->isNotEmpty()) {
                        $item['submenu'] = $children->toArray();
                    }

                    $dbMenu[] = $item;
                }
            }
        }

        // --- add menus NOT inside any header (orphans) ---
        $orphanMenus = $allMenus->whereNotIn('id', $usedMenuIds);
        foreach ($orphanMenus as $m) {
            $item = [
                'text' => $m->name,
                'url'  => $m->route ?: '#',
                'icon' => $m->icon ?: 'far fa-circle',
            ];

            $children = $build($m->id);
            if ($children->isNotEmpty()) {
                $item['submenu'] = $children->toArray();
            }

            $dbMenu[] = $item;
        }

        // merge with existing config menu
        $static = Config::get('adminlte.menu', []);
        Config::set('adminlte.menu', array_merge($static, $dbMenu));
    }
}