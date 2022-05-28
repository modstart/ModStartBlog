<?php

use Module\Banner\Util\BannerUtil;


class MBanner
{
    
    public static function all($position = 'home')
    {
        return BannerUtil::listByPositionWithCache($position);
    }
}