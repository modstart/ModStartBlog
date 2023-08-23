<?php


namespace Module\Blog\Util;


class UrlUtil
{
    public static function blog($blog)
    {
        return modstart_web_url('blog/' . $blog['id']);
    }

    public static function blogVisitPasswordVerify()
    {
        return modstart_api_url('blog/visit_password_verify');
    }
}
