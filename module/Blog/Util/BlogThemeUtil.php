<?php


namespace Module\Blog\Util;


use Module\Blog\Type\BlogDarkModeType;

class BlogThemeUtil
{
    public static function isDarkAuto()
    {
        if (!modstart_config('Blog_DarkModeEnable', false)) {
            return false;
        }
        $type = modstart_config('Blog_DarkModeType', BlogDarkModeType::AUTO);
        return BlogDarkModeType::AUTO == $type;
    }

    public static function isDarkTime()
    {
        if (!modstart_config('Blog_DarkModeEnable', false)) {
            return false;
        }
        $type = modstart_config('Blog_DarkModeType', BlogDarkModeType::AUTO);
        if (BlogDarkModeType::TIME == $type) {
            $start = modstart_config('Blog_DarkModeStart');
            $end = modstart_config('Blog_DarkModeEnd');
            if (!$start || !$end) {
                return false;
            }
            $time = date('H:i:s');
            if ($end > $start) {
                if ($time > $start && $time < $end) {
                    return true;
                }
            } else {
                if ($time < $end || $time > $start) {
                    return true;
                }
            }
            return false;
        }
        return false;
    }
}
