<?php


namespace Module\Blog\Util;


use Illuminate\Support\Facades\Cache;
use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Util\ArrayUtil;
use ModStart\Core\Util\TagUtil;
use Module\Blog\Model\Blog;

class BlogTagUtil
{
    public static function clearCache()
    {
        Cache::forget('BlogTags');
    }

    public static function all()
    {
        return Cache::rememberForever('BlogTags', function () {
            $blogs = Blog::published()->get(['tag'])->toArray();
            TagUtil::recordsString2Array($blogs, 'tag');
            $tags = [];
            foreach ($blogs as $blog) {
                foreach ($blog['tag'] as $t) {
                    if (isset($tags[$t])) {
                        $tags[$t]++;
                    } else {
                        $tags[$t] = 1;
                    }
                }
            }
            return $tags;
        });
    }

    public static function records()
    {
        $all = self::all();
        $records = array_build($all, function ($k, $v) {
            return [$k, ['name' => $k, 'count' => $v]];
        });
        $records = ArrayUtil::sortByKey($records, 'count', 'desc');
        return $records;
    }
}
