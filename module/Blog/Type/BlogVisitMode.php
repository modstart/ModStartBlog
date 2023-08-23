<?php


namespace Module\Blog\Type;


use ModStart\Core\Type\BaseType;

class BlogVisitMode implements BaseType
{
    const OPEN = 1;
    const PASSWORD = 2;

    public static function getList()
    {
        return [
            self::OPEN => '公开',
            self::PASSWORD => '密码访问'
        ];
    }

}
