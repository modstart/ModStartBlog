<?php


namespace Module\Vendor\Provider\SiteUrl;


use Module\Vendor\Provider\BizTrait;

class SiteUrlBiz
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
