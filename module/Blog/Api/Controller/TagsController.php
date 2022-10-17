<?php


namespace Module\Blog\Api\Controller;


use Illuminate\Routing\Controller;
use ModStart\Core\Input\Response;
use Module\Blog\Util\BlogTagUtil;


class TagsController extends Controller
{
    
    public function all()
    {
        $tags = BlogTagUtil::records();
        return Response::generateSuccessData([
            'tags' => $tags,
        ]);
    }
}
