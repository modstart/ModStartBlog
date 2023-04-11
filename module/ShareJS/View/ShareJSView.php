<?php


namespace Module\ShareJS\View;


use Illuminate\Support\Facades\View;

class ShareJSView
{
    public static function buttons($sites = 'weibo,qq,qzone,wechat', $param = [])
    {
        return View::make('module::ShareJS.View.inc.buttons', [
            'sites' => $sites,
            'param' => $param,
        ]);
    }
}
