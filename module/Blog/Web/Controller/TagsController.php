<?php


namespace Module\Blog\Web\Controller;


use ModStart\Core\Input\Response;
use ModStart\Module\ModuleBaseController;

class TagsController extends ModuleBaseController
{
    public function index(\Module\Blog\Api\Controller\TagsController $api)
    {
        $viewData = Response::tryGetData($api->all());
        $viewData['pageTitle'] = '博客标签';
        $viewData['pageKeywords'] = '博客标签';
        $viewData['pageDescription'] = '博客标签';
        return $this->view('blog.tags', $viewData);
    }
}
