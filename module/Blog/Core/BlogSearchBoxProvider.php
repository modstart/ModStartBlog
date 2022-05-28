<?php


namespace Module\Blog\Core;


use Module\Vendor\Provider\SearchBox\AbstractSearchBoxProvider;

class BlogSearchBoxProvider extends AbstractSearchBoxProvider
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

    public function url()
    {
        return modstart_web_url('blogs');
    }

}
