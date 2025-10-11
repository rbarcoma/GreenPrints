<?php

namespace App\Providers;

use App\Models\MenuHeaderModel;
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

        // --- Load all menus in sorted order ---
        $query = MenuModel::query();
        if (Schema::hasColumn('menus', 'sort')) {
            $query->orderBy('parent_id')->orderBy('sort')->orderBy('id');
        } else {
            $query->orderBy('parent_id')->orderBy('id');
        }

        $allMenus = $query->get();

        // --- Group menus by parent_id for recursive building ---
        $grouped = $allMenus->groupBy('parent_id');

        $build = function ($parentId) use (&$build, $grouped) {
            $children = $grouped->get($parentId, collect());

            return $children->map(function ($m) use (&$build) {
                $item = [
                    'text' => $m->name,
                    'url'  => $m->route ?: '#',
                    'icon' => $m->icon ?: 'far fa-circle',
                ];

                $subChildren = $build($m->id);
                if ($subChildren->isNotEmpty()) {
                    $item['submenu'] = $subChildren->toArray();
                }

                return $item;
            });
        };

        // --- Function to collect all descendant menu IDs recursively ---
        $collectIds = function ($menu, $grouped) use (&$collectIds) {
            $ids = [$menu->id];
            $children = $grouped->get($menu->id, collect());
            foreach ($children as $child) {
                $ids = array_merge($ids, $collectIds($child, $grouped));
            }
            return $ids;
        };

        $usedMenuIds = [];
        $dbMenu = [];

        // --- Process all Menu Headers and their assigned menus ---
        if (Schema::hasTable('menu_headers')) {
            $menuHeaders = MenuHeaderModel::all();

            foreach ($menuHeaders as $header) {
                $dbMenu[] = ['header' => $header->name];

                $menus = MenuModel::whereIn('id', $header->menu_ids ?? [])->get();
                foreach ($menus as $m) {
                    // mark menu and all its descendants as "used"
                    $usedMenuIds = array_merge($usedMenuIds, $collectIds($m, $grouped));

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

        // --- Add orphan menus (not under any header) ---
        $orphanMenus = $allMenus->whereNotIn('id', $usedMenuIds);
        foreach ($orphanMenus as $m) {
            // Skip child menus whose parent is already an orphan (avoid double nesting)
            if ($m->parent_id && $allMenus->contains('id', $m->parent_id)) {
                continue;
            }

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

        // --- Merge with AdminLTE static menu configuration ---
        $static = Config::get('adminlte.menu', []);
        Config::set('adminlte.menu', array_merge($static, $dbMenu));
    }
}
