<?php


namespace Module\Blog\Traits;


use ModStart\Admin\Layout\AdminPage;
use ModStart\Admin\Widget\SecurityTooltipBox;
use ModStart\Layout\Row;
use Module\AdminManager\Widget\ServerInfoWidget;
use Module\Blog\Widget\BlogInfoWidget;
use Module\Vendor\Admin\Widget\AdminWidgetDashboard;

trait AdminDashboardTrait
{
    public function dashboard()
    {
        /** @var AdminPage $page */
        $page = app(AdminPage::class);
        $page->pageTitle(L('Dashboard'))
            ->row(new SecurityTooltipBox())
            ->append(new Row(function (Row $row) {
                AdminWidgetDashboard::callIcon($row);
            }));
        AdminWidgetDashboard::call($page);
        $page->append(new BlogInfoWidget());
        $page->append(new ServerInfoWidget());
        return $page;
    }
}
