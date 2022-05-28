<?php


namespace Module\Vendor\Provider\Ocr;


use Module\Vendor\Provider\ProviderTrait;

class OcrProvider
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

    public static function first()
    {
        foreach (self::all() as $provider) {
            return $provider;
        }
        return null;
    }

}
