<?php

namespace Module\Vendor\Provider\SearchBox;


use Module\Vendor\Provider\ProviderTrait;

class SearchBoxProvider
{
    use ProviderTrait;

    
    public static function all()
    {
        
        $records = self::listAll();
        usort($records, function ($o1, $o2) {
            if ($o1->order() == $o2->order()) {
                return 0;
            }
            return $o1->order() > $o2->order() ? 1 : -1;
        });
        return $records;
    }

    
    public static function get($name)
    {
        return self::getByName($name);
    }

    public static function count()
    {
        return count(self::all());
    }
}
