<?php

namespace ModStart;

use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use ModStart\Core\Exception\BizException;
use ModStart\Module\ModuleManager;
use ModStart\Support\Manager\FieldManager;
use ModStart\Support\Manager\WidgetManager;


class ModStart
{
    public static $version = '3.5.0';

    public static $script = [];
    public static $style = [];
    public static $css = [];
    public static $js = [];

    
    public static function clearCache()
    {
        Cache::forget('ModStartServiceProviders');
        Cache::forget('ModStartAdminRoutes');
        Cache::forget('ModStartApiRoutes');
        Cache::forget('ModStartOpenApiRoutes');
        Cache::forget('ModStartWebRoutes');
        
        self::safeCleanOptimizedFile('bootstrap/cache/compiled.php');
        self::safeCleanOptimizedFile('bootstrap/cache/services.json');
        self::safeCleanOptimizedFile('bootstrap/cache/config.php');

        if (method_exists(ModuleManager::class, 'hotReloadSystemConfig')) {
            ModuleManager::hotReloadSystemConfig();
        }
    }

    private static function safeCleanOptimizedFile($file)
    {
        if (file_exists($path = base_path($file))) {
            @unlink($path);
        }
    }


    
    public static function scriptFile($scriptFile, $absolute = false)
    {
        if (!$absolute) {
            $scriptFile = base_path($scriptFile);
        }
        try {
            return self::script(file_get_contents($scriptFile));
        } catch (\Exception $e) {
            BizException::throws('FileNotFound -> ' . $scriptFile);
        }
    }

    
    public static function script($script = '')
    {
        $script = trim($script);
        if (!empty($script)) {
            self::$script = array_merge(self::$script, (array)$script);
            return;
        }
        return view('modstart::part.script', ['script' => array_unique(self::$script)]);
    }

    
    public static function styleFile($styleFile, $absolute = false)
    {
        if (!$absolute) {
            $styleFile = base_path($styleFile);
        }
        try {
            return self::style(file_get_contents($styleFile));
        } catch (\Exception $e) {
            BizException::throws('FileNotFound -> ' . $styleFile);
        }
    }

    
    public static function style($style = '')
    {
        $style = trim($style);
        if (!empty($style)) {
            self::$style = array_merge(self::$style, (array)$style);
            return;
        }
        static::$style = array_merge(
            static::$style,
            FieldManager::collectFieldAssets('style'),
            WidgetManager::collectWidgetAssets('style')
        );
        return view('modstart::part.style', ['style' => array_unique(self::$style)]);
    }

    
    public static function css($css = null)
    {
        if (!is_null($css)) {
            self::$css = array_merge(self::$css, (array)$css);
            return;
        }
        static::$css = array_merge(
            static::$css,
            FieldManager::collectFieldAssets('css')
        );
        return view('modstart::part.css', ['css' => array_unique(static::$css)]);
    }

    
    public static function js($js = null)
    {
        if (!is_null($js)) {
            self::$js = array_merge(self::$js, (array)$js);
            return;
        }
        static::$js = array_merge(
            static::$js,
            FieldManager::collectFieldAssets('js')
        );
        return view('modstart::part.js', ['js' => array_unique(static::$js)]);
    }


    
    public static function env()
    {
        if (PHP_VERSION_ID >= 80000) {
            return 'laravel9';
        }
        return 'laravel5';
    }
}
