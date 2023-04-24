<?php


namespace Module\Blog\Util;


class BlogThemeUtil
{
    public static function isDark()
    {
        if (!modstart_config('Blog_DarkModeEnable', false)) {
            return false;
        }
        $start = modstart_config('Blog_DarkModeStart');
        $end = modstart_config('Blog_DarkModeEnd');
        if (!$start || !$end) {
            return false;
        }
        $time = date('H:i:s');
        if (
            ($time > $start && $time < $end)
            ||
            ($time > $end && $time < $start)
        ) {
            return true;
        }
        return false;
    }
}
