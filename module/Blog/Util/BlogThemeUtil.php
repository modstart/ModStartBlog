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
        if ($end < $start) {
            $t = $start;
            $start = $end;
            $end = $t;
        }
        if ($time > $start && $time < $end) {
            return true;
        }
        return false;
    }
}
