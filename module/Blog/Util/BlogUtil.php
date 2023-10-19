<?php


namespace Module\Blog\Util;


use ModStart\Core\Dao\ModelUtil;
use Module\Blog\Core\BlogMemberFavBiz;
use Module\Blog\Core\BlogMemberLikeBiz;
use Module\MemberFav\Model\MemberFav;
use Module\MemberLike\Model\MemberLike;

class BlogUtil
{
    public static function updateFavoriteCount($id)
    {
        if (modstart_module_enabled('MemberFav')) {
            ModelUtil::update('blog', $id, [
                'favCount' => MemberFav::countBiz(BlogMemberFavBiz::NAME, $id),
            ]);
        }
    }

    public static function updateLikeCount($id)
    {
        if (modstart_module_enabled('MemberLike')) {
            ModelUtil::update('blog', $id, [
                'likeCount' => MemberLike::countBiz(BlogMemberLikeBiz::NAME, $id),
            ]);
        }
    }
}
