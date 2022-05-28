<?php


namespace Module\Blog\Core;


use Module\Vendor\Provider\HomePage\AbstractHomePageProvider;

class BlogHomePageProvider extends AbstractHomePageProvider
{
    const NAME = 'blog';

    public function title()
    {
        return 'ModStartBlog';
    }

    public function action()
    {
        return '\Module\Blog\Web\Controller\IndexController@index';
    }

}
