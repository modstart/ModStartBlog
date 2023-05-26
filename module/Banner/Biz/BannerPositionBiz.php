<?php


namespace Module\Banner\Biz;


use Module\Vendor\Provider\BizTrait;


class BannerPositionBiz
{
    use BizTrait;

    public static function registerQuick($name, $title, $remark = null)
    {
        self::register(QuickBannerPositionBiz::make($name, $title, $remark));
    }

    
    public static function all()
    {
        return self::listAll();
    }

    
    public static function get($name)
    {
        return self::getByName($name);
    }
}
