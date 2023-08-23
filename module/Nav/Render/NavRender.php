<?php


namespace Module\Nav\Render;


use Illuminate\Support\Facades\View;

/**
 * Class NavRender
 * @package Module\Nav\Render
 * @deprecated delete at 2023-04-07
 */
class NavRender
{
    public static function position($position)
    {
        return View::make('module::Nav.View.inc.nav', [
            'position' => $position,
        ])->render();
    }
}
