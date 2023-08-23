<?php


namespace Module\Blog\Admin\Controller;


use Illuminate\Routing\Controller;
use ModStart\Admin\Concern\HasAdminQuickCRUD;
use ModStart\Admin\Layout\AdminCRUDBuilder;
use ModStart\Form\Form;
use ModStart\Grid\GridFilter;
use ModStart\Support\Concern\HasFields;
use Module\Blog\Type\BlogCategoryTemplateView;
use Module\Blog\Util\BlogCategoryUtil;

class BlogCategoryController extends Controller
{
    use HasAdminQuickCRUD;

    protected function crud(AdminCRUDBuilder $builder)
    {
        $builder
            ->init('blog_category')
            ->field(function ($builder) {
                /** @var HasFields $builder */
                $builder->id('id', 'ID');
                $builder->text('title', '名称');
                $builder->image('cover', '封面')->help('默认模板不显示，可安装更多模板查看效果');
                $builder->text('keywords', '关键词');
                $builder->textarea('description', '描述');
                $builder->select('templateView', '列表模板')->optionType(BlogCategoryTemplateView::class);
                $builder->display('blogCount', '博客数')->listable(true)->addable(false)->editable(false);
                $builder->display('created_at', L('Created At'))->listable(false);
                $builder->display('updated_at', L('Updated At'))->listable(false);
            })
            ->gridFilter(function (GridFilter $filter) {
                $filter->eq('id', L('ID'));
                $filter->like('title', L('Title'));
            })
            ->hookChanged(function (Form $form) {
                BlogCategoryUtil::clearCache();
            })
            ->title('博客分类')
            ->asTree()
            ->treeMaxLevel(1);
    }
}
