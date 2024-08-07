<?php

namespace Module\Blog\Web\Controller;

use ModStart\Core\Input\Request;
use ModStart\Core\Input\Response;
use ModStart\Core\Util\ArrayUtil;
use ModStart\Core\Util\PageHtmlUtil;
use ModStart\Module\ModuleBaseController;

class IndexController extends ModuleBaseController
{
    public function index(\Module\Blog\Api\Controller\BlogController $api)
    {
        $viewData = Response::tryGetData($api->paginate());
        $viewData['pageHtml'] = PageHtmlUtil::render($viewData['total'], $viewData['pageSize'], $viewData['page'], '?' . Request::mergeQueries(['page' => ['{page}']]));

        $viewData['pageTitle'] = ArrayUtil::firstValidValue(
            modstart_config('siteName') . ' | ' . modstart_config('siteSlogan'),
            modstart_config('Blog_SeoTitle')
        );
        $viewData['pageKeywords'] = ArrayUtil::firstValidValue(
            modstart_config('siteKeywords'),
            modstart_config('Blog_SeoKeywords')
        );
        $viewData['pageDescription'] = ArrayUtil::firstValidValue(
            modstart_config('siteDescription'),
            modstart_config('Blog_SeoDescription')
        );

        return $this->view('blog.index', $viewData);
    }
}
