<?php


namespace Module\Blog\Type;


use ModStart\Core\Type\BaseType;

class BlogDarkModeType implements BaseType
{
    const AUTO = 'auto';
    const TIME = 'time';

    public static function getList()
    {
        return [
            self::AUTO => '跟随系统',
            self::TIME => '按时间段'
        ];
    }

}
