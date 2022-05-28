<?php


namespace Module\Nav\Biz;


use Module\Vendor\Biz\BizTrait;

class NavPositionBiz
{
    use BizTrait;

    
    public static function all()
    {
        return self::listAll();
    }

    
    public static function get($name)
    {
        return self::getByName($name);
    }
}
