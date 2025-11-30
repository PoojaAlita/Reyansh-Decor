<?php

namespace App\Helpers;

use App\Models\AdminPage;

class MenuBuilder
{
    public static function build($menuData, $parentId = 0)
    {
        return $menuData
            ->where('parent_id', $parentId)
            ->where('isshown', 1)
            ->sortBy('sortorder')
            ->map(function ($menu) use ($menuData) {

                $children = self::build($menuData, $menu->id);

                return (object)[
                    'id' => $menu->id,
                    'title' => $menu->title,
                    'icon' => $menu->icon,
                    'url' => $menu->url,
                    'children' => $children
                ];
            });
    }
}
