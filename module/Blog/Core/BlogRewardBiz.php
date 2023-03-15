<?php


namespace Module\Blog\Core;


use Module\Reward\Biz\AbstractRewardBiz;

class BlogRewardBiz extends AbstractRewardBiz
{
    const NAME = 'blog';

    public function name()
    {
        return self::NAME;
    }

    public function title()
    {
        return '博客';
    }

}
