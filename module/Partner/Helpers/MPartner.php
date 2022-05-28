<?php



class MPartner
{
    
    public static function all($position = 'home')
    {
        return \Module\Partner\Util\PartnerUtil::listByPositionWithCache($position);
    }
}