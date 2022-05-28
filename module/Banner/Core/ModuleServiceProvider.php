<?php

namespace Module\Banner\Core;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use ModStart\Admin\Config\AdminMenu;
use ModStart\Module\ModuleClassLoader;

class ModuleServiceProvider extends ServiceProvider
{
    
    public function boot(Dispatcher $events)
    {
        if (method_exists(ModuleClassLoader::class, 'addClass')) {
            ModuleClassLoader::addClass('MBanner', __DIR__ . '/../Helpers/MBanner.php');
        }

        AdminMenu::register([
            [
                'title' => '物料管理',
                'icon' => 'description',
                'sort' => 200,
                'children' => [
                    [
                        'title' => '轮播图片',
                        'url' => '\Module\Banner\Admin\Controller\BannerController@index',
                    ],
                ]
            ]
        ]);
    }

    
    public function register()
    {

    }
}
