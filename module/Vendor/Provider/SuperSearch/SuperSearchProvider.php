<?php


namespace Module\Vendor\Provider\SuperSearch;


use Module\Vendor\Provider\ProviderTrait;

class SuperSearchProvider
{
    use ProviderTrait;

    
    public static function all()
    {
        return self::listAll();
    }

    
    public static function get($name)
    {
        return self::getByName($name);
    }
}
