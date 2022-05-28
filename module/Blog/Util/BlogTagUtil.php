<?php


namespace Module\Blog\Util;


use Illuminate\Support\Facades\Cache;
use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Util\TagUtil;

class BlogTagUtil
{
    public static function clearCache()
    {
        Cache::forget('BlogTags');
    }

    public static function all()
    {
        return Cache::rememberForever('BlogTags', function () {
            $blogs = ModelUtil::all('blog', [], ['tag']);
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
}
