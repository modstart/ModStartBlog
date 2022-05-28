<?php


namespace ModStart\Core\Util;


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;


class RouteUtil
{
    
    public static function parseControllerMethod()
    {
        $routeAction = Route::currentRouteAction();
        $pieces = explode('@', $routeAction);
        if (isset($pieces[0])) {
            $urlController = $pieces[0];
        } else {
            $urlController = null;
        }
        if (isset($pieces[1])) {
            $urlMethod = $pieces[1];
        } else {
            $urlMethod = null;
        }
        if (!Str::startsWith($urlController, '\\')) {
            $urlController = '\\' . $urlController;
        }

        return [
            $urlController,
            $urlMethod
        ];
    }
}
