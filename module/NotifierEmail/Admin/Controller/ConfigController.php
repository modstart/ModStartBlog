<?php

namespace Module\NotifierEmail\Admin\Controller;

use Illuminate\Routing\Controller;
use ModStart\Admin\Layout\AdminConfigBuilder;
use Module\Vendor\Provider\Notifier\NotifierBiz;

class ConfigController extends Controller
{
    public function setting(AdminConfigBuilder $builder)
    {
        $builder->pageTitle('邮箱消息通知');
        $builder->switch('NotifierEmail_Enable', '开启邮件通知');
        $builder->text('NotifierEmail_DefaultEmail', '默认通知邮箱')->help('多个地址使用,分割');
        foreach (NotifierBiz::listAll() as $item) {
            $builder->text('NotifierEmail_Biz_' . $item->name(), $item->title() . '-通知邮箱')->help('多个地址使用,分割');
        }
        $builder->formClass('wide-lg');
        return $builder->perform();
    }

}
