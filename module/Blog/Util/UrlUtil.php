<?php


namespace Module\Blog\Util;


class UrlUtil
{
    public static function category($category)
    {
        return modstart_web_url('blogs', [
            'categoryId' => $category['id'],
        ]);
    }

    public static function blog($blog)
    {
        return modstart_web_url('blog/' . $blog['id']);
    }

    public static function blogVisitPasswordVerify()
    {
        return modstart_api_url('blog/visit_password_verify');
    }

    public static function tag($tag)
    {
        return modstart_web_url('blogs', ['keywords' => $tag]);
    }

    public static function search($keywords = '')
    {
        $param = [];
        if ($keywords) {
            $param['keywords'] = $keywords;
        }
        return modstart_web_url('blogs', $param);
    }
}
