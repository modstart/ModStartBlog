<?php


namespace Module\Blog\Admin\Controller;

use Illuminate\Routing\Controller;
use ModStart\Admin\Layout\AdminConfigBuilder;
use Module\Vendor\Provider\Captcha\CaptchaProvider;

class ConfigController extends Controller
{
    public function index(AdminConfigBuilder $builder)
    {
        $builder->pageTitle('博客设置');
        $builder->text('Blog_Name', '博客名称');
        $builder->text('Blog_Slogan', '博客标语');
        $builder->image('Blog_Avatar', '博客头像');
        $builder->select('Blog_BlogCaptchaProvider', '博客评论验证')->options(CaptchaProvider::nameTitleMap());
        $builder->select('Blog_MessageCaptchaProvider', '博客留言验证')->options(CaptchaProvider::nameTitleMap());
        $builder->formClass('wide');
        return $builder->perform();
    }

    public function about(AdminConfigBuilder $builder)
    {
        $builder->pageTitle('关于博主');
        $builder->richHtml('Blog_AboutContent', '介绍内容');
        $builder->formClass('wide');
        return $builder->perform();
    }
}
