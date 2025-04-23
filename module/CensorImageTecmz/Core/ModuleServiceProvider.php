<?php

namespace Module\CensorImageTecmz\Core;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use ModStart\Admin\Config\AdminMenu;
use Module\Vendor\Provider\CensorImage\CensorImageProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        AdminMenu::register(function () {
            return [
                [
                    'title' => L('Site Manage'),
                    'icon' => 'cog',
                    'sort' => 400,
                    'children' => [
                        [
                            'title' => '接口设置',
                            'children' => [
                                [
                                    'title' => '魔众图片智能审核',
                                    'url' => '\Module\CensorImageTecmz\Admin\Controller\ConfigController@index',
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        });
        CensorImageProvider::register(CensorImageTecmzProvider::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
