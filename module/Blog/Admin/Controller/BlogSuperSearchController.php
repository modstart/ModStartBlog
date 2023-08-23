<?php


namespace Module\Blog\Admin\Controller;

use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Input\Response;
use Module\Blog\Core\BlogSuperSearchBiz;
use Module\Vendor\Provider\SuperSearch\Controller\AbstractAdminSuperSearchController;

class BlogSuperSearchController extends AbstractAdminSuperSearchController
{
    public function index()
    {
        return $this->renderIndex(BlogSuperSearchBiz::provider(), [
            'blog'
        ]);
    }

    /**
     * @param $provider
     * @param $bucket
     * @param $nextId
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     * @deprecated
     */
    public function sync($provider, $bucket, $nextId)
    {
        switch ($bucket) {
            case 'blog':
                $ret = ModelUtil::batch('blog', $nextId, 1000);
                BlogSuperSearchBiz::syncUpsert($ret['records'], false);
                $data = [];
                $data['count'] = count($ret['records']);
                $data['nextId'] = $ret['nextId'];
                return Response::generateSuccessData($data);
        }
        return Response::send(-1, '请求错误');
    }
}
