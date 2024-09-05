<?php

namespace Module\Blog\Core;

use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Util\TagUtil;
use Module\Blog\Model\Blog;
use Module\TagManager\Biz\AbstractTagManagerBiz;

class BlogTagManagerBiz extends AbstractTagManagerBiz
{
    const NAME = 'Blog';

    public function name()
    {
        return self::NAME;
    }

    public function title()
    {
        return '博客系统';
    }

    public function searchUrl($tag)
    {
        return modstart_web_url('blogs', ['keywords' => $tag]);
    }

    public function syncBatch($nextId)
    {
        $batch = ModelUtil::batch(Blog::class, $nextId, 100);
        TagUtil::recordsString2Array($batch['records'], ['tag']);
        $tags = [];
        foreach ($batch['records'] as $record) {
            $tags = array_merge($tags, $record['tag']);
        }
        $data = [];
        $data['nextId'] = $batch['nextId'];
        $data['tags'] = $tags;
        $data['finish'] = empty($batch['records']);
        return $data;
    }


}
