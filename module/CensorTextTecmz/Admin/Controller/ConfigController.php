<?php

namespace Module\CensorTextTecmz\Admin\Controller;

use Illuminate\Routing\Controller;
use ModStart\Admin\Layout\AdminConfigBuilder;
use Module\Vendor\Tecmz\TecmzUtil;

class ConfigController extends Controller
{
    public function index(AdminConfigBuilder $builder)
    {
        $builder->pageTitle('魔众文本智能审核');
        $builder->text('CensorTextTecmz_AppId', 'AppId')
            ->help('<div>访问 <a href="' . TecmzUtil::url('CensorText') . '" target="_blank">' . TecmzUtil::url('CensorText') . '</a> 申请</div>');
        $builder->text('CensorTextTecmz_AppSecret', 'AppSecret');
        return $builder->perform();
    }

}
