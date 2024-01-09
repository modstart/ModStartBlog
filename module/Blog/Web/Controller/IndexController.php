<?php

namespace Module\Blog\Web\Controller;

use ModStart\Core\Input\Request;
use ModStart\Core\Input\Response;
use ModStart\Core\Util\PageHtmlUtil;
use ModStart\Module\ModuleBaseController;

class IndexController extends ModuleBaseController
{
    public function index(\Module\Blog\Api\Controller\BlogController $api)
    {
        $viewData = Response::tryGetData($api->paginate());
        $viewData['pageHtml'] = PageHtmlUtil::render($viewData['total'], $viewData['pageSize'], $viewData['page'], '?' . Request::mergeQueries(['page' => ['{page}']]));
        $viewData['pageTitle'] = modstart_config('Blog_SeoTitle');
        if (empty($viewData['pageTitle'])) {
            $viewData['pageTitle'] = modstart_config('siteName') . ' | ' . modstart_config('siteSlogan');
        }
        $viewData['pageKeywords'] = modstart_config('Blog_SeoKeywords');
        if (empty($viewData['pageKeywords'])) {
            $viewData['pageKeywords'] = modstart_config('siteKeywords');
        }
        $viewData['pageDescription'] = modstart_config('Blog_SeoDescription');
        if (empty($viewData['pageDescription'])) {
            $viewData['pageDescription'] = modstart_config('siteDescription');
        }
        return $this->view('blog.index', $viewData);
    }
}
