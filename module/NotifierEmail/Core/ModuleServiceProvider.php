<?php

namespace Module\NotifierEmail\Core;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use ModStart\Admin\Config\AdminMenu;
use Module\NotifierEmail\Driver\EmailNotifierProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        if (modstart_config('NotifierEmail_Enable', false)) {
            $this->app['config']->set('NotifierProviders', array_merge(
                $this->app['config']->get('NotifierProviders', []),
                ['NotifierProvider_Email']
            ));
            $this->app->bind('NotifierProvider_Email', function () {
                return new EmailNotifierProvider();
            });
        }
        AdminMenu::register(function () {
            return [
                [
                    'title' => '功能设置',
                    'icon' => 'tools',
                    'sort' => 300,
                    'children' => [
                        [
                            'title' => '邮箱消息通知',
                            'url' => '\Module\NotifierEmail\Admin\Controller\ConfigController@setting',
                        ],
                    ]
                ]
            ];
        });
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
