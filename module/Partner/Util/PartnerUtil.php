<?php

namespace Module\Partner\Util;

use Illuminate\Support\Facades\Cache;
use ModStart\Core\Assets\AssetsUtil;
use ModStart\Core\Dao\ModelUtil;
use Module\Partner\Type\PartnerPosition;

class PartnerUtil
{
    const CACHE_KEY_PREFIX = 'partner:';

    
    public static function listByPosition($position = 'home')
    {
        $records = ModelUtil::model('partner')
            ->where([
                'position' => $position,
                'enable' => true,
            ])
            ->orderBy('sort', 'asc')->get()->toArray();
        foreach ($records as $k => $v) {
            if (!empty($v['logo'])) {
                $records[$k]['logo'] = AssetsUtil::fixFull($v['logo']);
            }
        }
        return $records;
    }

    
    public static function listByPositionWithCache($position = 'home', $minutes = 60)
    {
        return Cache::remember(self::CACHE_KEY_PREFIX . $position, $minutes, function () use ($position) {
            return self::listByPosition($position);
        });
    }

    public static function clearCache()
    {
        foreach (PartnerPosition::getList() as $k => $_) {
            Cache::forget(self::CACHE_KEY_PREFIX . $k);
        }
    }
}
