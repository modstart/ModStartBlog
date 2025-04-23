<?php

namespace Module\CensorImageTecmz\Admin\Controller;

use Illuminate\Routing\Controller;
use ModStart\Admin\Layout\AdminConfigBuilder;
use Module\Vendor\Tecmz\TecmzUtil;

class ConfigController extends Controller
{
    public function index(AdminConfigBuilder $builder)
    {
        $builder->pageTitle('图片智能审核');
        $builder->text('CensorImageTecmz_AppId', 'AppId');
        $builder->text('CensorImageTecmz_AppSecret', 'AppSecret');
        $builder->display('_', '')->addable(true)
            ->help('<div>访问 <a href="' . TecmzUtil::url('CensorImage') . '" target="_blank">' . TecmzUtil::url('CensorImage') . '</a> 申请</div>');
        return $builder->perform();
    }

}
