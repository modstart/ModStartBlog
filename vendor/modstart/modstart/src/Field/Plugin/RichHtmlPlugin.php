<?php


namespace ModStart\Field\Plugin;


class RichHtmlPlugin
{
    
    private static $list = [];

    public static function reigster($plugin)
    {
        self::$list[] = $plugin;
    }

    
    public static function all()
    {
        foreach (self::$list as $k => $plugin) {
            if (is_string($plugin)) {
                self::$list[$k] = app($plugin);
            }
        }
        return self::$list;
    }

}
