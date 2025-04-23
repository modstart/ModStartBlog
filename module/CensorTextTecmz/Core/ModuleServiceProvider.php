<?php

namespace Module\CensorTextTecmz\Core;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use ModStart\Admin\Config\AdminMenu;
use Module\Vendor\Provider\CensorText\CensorTextProvider;

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
                                    'title' => '魔众文本智能审核',
                                    'url' => '\Module\CensorTextTecmz\Admin\Controller\ConfigController@index',
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        });
        CensorTextProvider::register(CensorTextTecmzProvider::class);
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
