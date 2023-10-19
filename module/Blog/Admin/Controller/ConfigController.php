<?php


namespace Module\Blog\Admin\Controller;

use Illuminate\Routing\Controller;
use ModStart\Admin\Layout\AdminConfigBuilder;
use ModStart\Support\Concern\HasFields;
use Module\Blog\Type\BlogDarkModeType;
use Module\Vendor\Provider\Captcha\CaptchaProvider;
use Module\Vendor\Provider\SuperSearch\SuperSearchProvider;

class ConfigController extends Controller
{
    public function index(AdminConfigBuilder $builder)
    {
        $builder->pageTitle('博客设置');
        $builder->text('Blog_Name', '博客名称');
        $builder->text('Blog_Slogan', '博客标语');
        $builder->image('Blog_Avatar', '博客头像');
        $builder->text('Blog_ContactQQ', '联系方式-QQ');
        $builder->text('Blog_ContactEmail', '联系方式-邮箱');
        $builder->text('Blog_ContactWeibo', '联系方式-微博');
        $builder->text('Blog_ContactWechat', '联系方式-微信');
        $builder->select('Blog_BlogCaptchaProvider', '博客评论验证')->options(CaptchaProvider::nameTitleMap());
        $builder->select('Blog_MessageCaptchaProvider', '博客留言验证')->options(CaptchaProvider::nameTitleMap());
        $builder->select('Blog_BlogSuperSearchProvider', '博客超级搜索驱动')->options(SuperSearchProvider::allDefaultMap());
        $builder->switch('Blog_DarkModeEnable', '启用暗黑模式')
            ->when('=', true, function ($builder) {
                /** @var HasFields $builder */
                $builder->radio('Blog_DarkModeType', '暗黑模式')
                    ->optionType(BlogDarkModeType::class)
                    ->when('=', BlogDarkModeType::TIME, function ($builder) {
                        /** @var HasFields $builder */
                        $builder->time('Blog_DarkModeStart', '开始');
                        $builder->time('Blog_DarkModeEnd', '结束');
                    })
                    ->defaultValue(BlogDarkModeType::AUTO);
            });
        $builder->number('Blog_PanelTagLimit', '侧边栏标签数量')->help('0为不限制')->defaultValue(0);
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
