<?php

namespace Module\Blog\Core;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use ModStart\Admin\Config\AdminMenu;
use ModStart\Admin\Widget\DashboardItemA;
use ModStart\Core\Dao\ModelUtil;
use ModStart\Layout\Row;
use ModStart\Module\ModuleClassLoader;
use Module\Banner\Biz\BannerPositionBiz;
use Module\Blog\Util\BlogCategoryUtil;
use Module\Partner\Biz\PartnerPositionBiz;
use Module\Reward\Biz\RewardBiz;
use Module\Vendor\Admin\Widget\AdminWidgetDashboard;
use Module\Vendor\Admin\Widget\AdminWidgetLink;
use Module\Vendor\Provider\HomePage\HomePageProvider;
use Module\Vendor\Provider\SearchBox\SearchBoxProvider;
use Module\Vendor\Provider\SiteUrl\SiteUrlBiz;
use Module\Vendor\Provider\SuperSearch\SuperSearchBiz;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        AdminWidgetLink::register(function () {
            $categories = [];
            foreach (BlogCategoryUtil::categoryTree() as $item) {
                $categories[] = ['分类-' . $item['title'], modstart_web_url('blogs', ['categoryId' => $item['id']])];
            }
            return AdminWidgetLink::build('博客', array_merge([
                ['博客首页', modstart_web_url('blog')],
                ['博客列表', modstart_web_url('blogs')],
                ['博客留言', modstart_web_url('blog/message')],
                ['博客归档', modstart_web_url('blog/archive')],
                ['博客标签', modstart_web_url('blog/tags')],
                ['关于博主', modstart_web_url('blog/about')],
            ], $categories));
        });
        AdminWidgetDashboard::registerIcon(function (Row $row) {
            $row->column(3, DashboardItemA::makeIconNumberTitle(
                'iconfont icon-book', ModelUtil::count('blog'), '博客总数',
                modstart_admin_url('blog/blog')
            ));
            $row->column(3, DashboardItemA::makeIconNumberTitle(
                'iconfont icon-comment', ModelUtil::count('blog_comment'), '博客评论',
                modstart_admin_url('blog/comment')
            ));
            $row->column(3, DashboardItemA::makeIconNumberTitle(
                'iconfont icon-comment', ModelUtil::count('blog_message'), '留言总数',
                modstart_admin_url('blog/message')
            ));
            $row->column(3, DashboardItemA::makeIconNumberTitle(
                'iconfont icon-comment', ModelUtil::count('blog_category'), '博客分类',
                modstart_admin_url('blog/category')
            ));
        });
        AdminMenu::register(function () {
            return [
                [
                    'title' => '博客管理',
                    'icon' => 'description',
                    'sort' => 100,
                    'children' => [
                        [
                            'title' => '博客管理',
                            'url' => '\Module\Blog\Admin\Controller\BlogController@index',
                        ],
                        [
                            'title' => '博客评论',
                            'url' => '\Module\Blog\Admin\Controller\BlogCommentController@index',
                        ],
                        [
                            'title' => '博客留言',
                            'url' => '\Module\Blog\Admin\Controller\BlogMessageController@index',
                        ],
                        [
                            'title' => '博客分类',
                            'url' => '\Module\Blog\Admin\Controller\BlogCategoryController@index',
                        ],
                        [
                            'title' => '博客设置',
                            'url' => '\Module\Blog\Admin\Controller\ConfigController@index',
                        ],
                        [
                            'title' => '关于博主',
                            'url' => '\Module\Blog\Admin\Controller\ConfigController@about',
                        ],
                        [
                            'title' => '超级搜索',
                            'url' => '\Module\Blog\Admin\Controller\BlogSuperSearchController@index',
                        ],
                    ],
                ],
            ];
        });
        HomePageProvider::register(BlogHomePageProvider::class);
        SearchBoxProvider::register(BlogSearchBoxProvider::class);
        ModuleClassLoader::addClass('MBlog', __DIR__ . '/MBlog.php');
        BannerPositionBiz::registerQuick('Blog', '博客系统');
        PartnerPositionBiz::registerQuick('Blog', '博客系统');
        SiteUrlBiz::register(BlogSiteUrlBiz::class);
        SuperSearchBiz::register(BlogSuperSearchBiz::class);
        if (modstart_module_enabled('Reward')) {
            RewardBiz::register(BlogRewardBiz::class);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
