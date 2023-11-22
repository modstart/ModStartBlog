<?php


namespace Module\Blog\Web\Controller;


use Illuminate\Routing\Controller;
use ModStart\Module\ModuleBaseController;

class AboutController extends ModuleBaseController
{
    public function index()
    {
        return $this->view('blog.about', [
            'pageTitle' => '关于博主',
            'pageKeywords' => '关于博主',
            'pageDescription' => '关于博主',
        ]);
    }
}
