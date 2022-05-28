<?php


namespace Module\Blog\Web\Controller;


use ModStart\Core\Input\InputPackage;
use ModStart\Core\Input\Request;
use ModStart\Core\Input\Response;
use ModStart\Core\Util\PageHtmlUtil;
use ModStart\Module\ModuleBaseController;

class BlogController extends ModuleBaseController
{
    public function index(\Module\Blog\Api\Controller\BlogController $api)
    {
        $viewData = Response::tryGetData($api->paginate());
        $viewData['pageHtml'] = PageHtmlUtil::render($viewData['total'], $viewData['pageSize'], $viewData['page'], '?' . Request::mergeQueries(['page' => ['{page}']]));
        return $this->view('blog.list', $viewData);
    }

    public function show(\Module\Blog\Api\Controller\BlogController $api, $id)
    {
        InputPackage::mergeToInput('id', $id);
        $viewData = Response::tryGetData($api->get());
        $viewData['commentPageHtml'] = PageHtmlUtil::render($viewData['commentTotal'], $viewData['commentPageSize'], $viewData['commentPage'], '?commentPage={page}');
        return $this->view('blog.show', $viewData);
    }

}
