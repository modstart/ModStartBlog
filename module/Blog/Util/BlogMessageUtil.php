<?php


namespace Module\Blog\Util;


use Module\Blog\Model\BlogMessage;
use Module\Blog\Type\BlogMessageStatus;

class BlogMessageUtil
{
    public static function latest($limit)
    {
        $records = BlogMessage::where([
            'status' => BlogMessageStatus::VERIFY_SUCCESS
        ])->orderBy('id', 'desc')->limit($limit)->get()->toArray();
        return $records;
    }
}
