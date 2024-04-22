<?php

namespace Module\Blog\Core;

use Module\Blog\Model\Blog;
use Module\Vendor\Provider\Schedule\AbstractScheduleBiz;

class BlogAutoPostScheduleBiz extends AbstractScheduleBiz
{
    const NAME = 'Blog';

    public function cron()
    {
        return $this->cronEveryMinute();
    }

    public function title()
    {
        return '博客自动发布';
    }

    public function run()
    {
        Blog::unPublished()
            ->where('postTime', '<=', date('Y-m-d H:i:s'))
            ->update(['isPublished' => true]);
    }

}
