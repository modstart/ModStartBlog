<?php

namespace Module\CensorTextTecmz\Admin\Controller;

use Illuminate\Routing\Controller;
use ModStart\Admin\Layout\AdminConfigBuilder;
use Module\Vendor\Tecmz\TecmzUtil;

class ConfigController extends Controller
{
    public function index(AdminConfigBuilder $builder)
    {
        $builder->pageTitle('文本智能审核');
        $builder->text('CensorTextTecmz_AppId', 'AppId');
        $builder->text('CensorTextTecmz_AppSecret', 'AppSecret');
        $builder->display('_', '')->addable(true)
            ->help('<div>访问 <a href="' . TecmzUtil::url('CensorText') . '" target="_blank">' . TecmzUtil::url('CensorText') . '</a> 申请</div>');
        return $builder->perform();
    }

}
