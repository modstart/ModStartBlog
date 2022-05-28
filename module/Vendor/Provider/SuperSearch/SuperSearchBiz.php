<?php


namespace Module\Vendor\Provider\SuperSearch;


use Module\Vendor\Biz\BizTrait;

class SuperSearchBiz
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
