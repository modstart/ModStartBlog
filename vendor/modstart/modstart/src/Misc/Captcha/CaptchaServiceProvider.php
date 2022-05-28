<?php

namespace ModStart\Misc\Captcha;

use Illuminate\Support\ServiceProvider;


class CaptchaServiceProvider extends ServiceProvider
{

    
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../../config/captcha.php' => config_path('captcha.php')
        ], 'config');
    }

    
    public function register()
    {
                $this->mergeConfigFrom(
            __DIR__ . '/../../../config/captcha.php', 'captcha'
        );

        $this->app->bind('captcha', function ($app) {
            return new Captcha(
                $app['Illuminate\Filesystem\Filesystem'],
                $app['Illuminate\Config\Repository'],
                $app['Intervention\Image\ImageManager'],
                $app['Illuminate\Session\Store'],
                $app['Illuminate\Hashing\BcryptHasher'],
                $app['Illuminate\Support\Str']
            );
        });
    }

}
