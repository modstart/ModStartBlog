<?php

namespace Module\EmailSmtp\Core;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use ModStart\Admin\Config\AdminMenu;
use Module\EmailSmtp\Provider\SmtpMailSenderProvider;
use Module\Vendor\Provider\MailSender\MailSenderProvider;

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
                            'title' => '短信邮箱',
                            'children' => [
                                [
                                    'title' => 'SMTP邮箱',
                                    'url' => '\Module\EmailSmtp\Admin\Controller\ConfigController@setting',
                                ],
                            ]
                        ]
                    ]
                ]
            ];
        });

        if (modstart_config('systemEmailEnable', false)) {
            MailSenderProvider::register(SmtpMailSenderProvider::class);
            $this->app['config']->set('EmailSenderProvider', SmtpMailSenderProvider::NAME);
        }
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
