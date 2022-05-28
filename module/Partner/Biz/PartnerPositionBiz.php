<?php


namespace Module\Partner\Biz;


use Module\Vendor\Biz\BizTrait;

class PartnerPositionBiz
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
