<?php


namespace Module\Blog\Util;


use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Util\TagUtil;
use Module\Blog\Model\BlogComment;
use Module\Blog\Type\BlogCommentStatus;

class BlogCommentUtil
{
    public static function latest($limit)
    {
        $records = BlogComment::with(['blog'])
            ->where([
                'status' => BlogCommentStatus::VERIFY_SUCCESS
            ])->orderBy('id', 'desc')->limit($limit)->get()->toArray();
        foreach ($records as $i => $v) {
            ModelUtil::decodeRecordJson($v['blog'], 'images');
            $v['blog']['tag'] = TagUtil::string2Array($v['blog']['tag']);
            $records[$i]['blog'] = BlogUtil::buildRecord($v['blog']);
        }
        return $records;
    }
}
