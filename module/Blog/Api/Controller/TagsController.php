<?php


namespace Module\Blog\Api\Controller;


use Illuminate\Routing\Controller;
use ModStart\Core\Input\Response;
use Module\Blog\Util\BlogTagUtil;

/**
 * @Api 博客系统
 */
class TagsController extends Controller
{
    /**
     * @Api 获取博客标签
     * @ApiDesc 获取博客所有标签及对应文章数量
     * @ApiMethod post
     * @ApiResponseData {
     *  "tags":[
     *    {
     *      "name":"标签",
     *      "count":1
     *    }
     *  ]
     * }
     */
    public function all()
    {
        $tags = BlogTagUtil::records();
        return Response::generateSuccessData([
            'tags' => $tags,
        ]);
    }
}
