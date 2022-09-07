<?php


namespace Module\Vendor\Provider\PersonVerify;


use Module\Vendor\Provider\ProviderTrait;

class PersonVerifyIdCardProvider
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

    public static function firstResponse($name, $idCardNumber, $param = [])
    {
        $provider = self::first();
        if (!$provider) {
            return null;
        }
        return $provider->verify($name, $idCardNumber, $param);
    }
}
