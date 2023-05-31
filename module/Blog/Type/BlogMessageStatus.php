<?php


namespace Module\Blog\Type;


use ModStart\Core\Type\BaseType;

class BlogMessageStatus implements BaseType
{
    const WAIT_VERIFY = 1;
    const VERIFY_SUCCESS = 2;
    const VERIFY_FAIL = 3;

    public static function getList()
    {
        return [
            self::WAIT_VERIFY => '待审核',
            self::VERIFY_SUCCESS => '审核通过',
            self::VERIFY_FAIL => '审核失败',
        ];
    }


}
