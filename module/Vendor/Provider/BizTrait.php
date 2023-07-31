<?php


namespace Module\Vendor\Provider;

trait BizTrait
{
    
    private static $list = [];

    public static function register($biz)
    {
        self::$list[] = $biz;
    }

    public static function registerAll(...$bizs)
    {
        foreach ($bizs as $biz) {
            self::register($biz);
        }
    }

    public static function listAll()
    {
        foreach (self::$list as $k => $v) {
            if ($v instanceof \Closure) {
                self::$list[$k] = call_user_func($v);
            } else if (is_string($v)) {
                self::$list[$k] = app($v);
            }
        }
        return self::$list;
    }

    public static function getByName($name)
    {
        foreach (self::listAll() as $item) {
            if ($item->name() == $name) {
                return $item;
            }
        }
        return null;
    }

    
    public static function allMap()
    {
        return array_build(self::listAll(), function ($k, $v) {
            return [
                $v->name(), $v->title()
            ];
        });
    }

    public static function allMapEnabled()
    {
        return array_build(array_filter(self::listAll(), function ($v) {
            return $v->enable();
        }), function ($k, $v) {
            return [
                $v->name(), $v->title()
            ];
        });
    }
}
