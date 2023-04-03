<?php


namespace Module\Blog\Admin\Controller;


use Illuminate\Routing\Controller;
use ModStart\Admin\Concern\HasAdminQuickCRUD;
use ModStart\Admin\Layout\AdminCRUDBuilder;
use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Input\Response;
use ModStart\Field\Tags;
use ModStart\Form\Form;
use ModStart\Grid\GridFilter;
use ModStart\Repository\RepositoryUtil;
use ModStart\Support\Concern\HasFields;
use Module\Blog\Util\BlogCategoryUtil;
use Module\Blog\Util\BlogTagUtil;
use Module\Vendor\Provider\SiteUrl\SiteUrlProvider;

class BlogController extends Controller
{
    use HasAdminQuickCRUD;

    protected function crud(AdminCRUDBuilder $builder)
    {
        $updatedCategoryIds = [];
        $builder
            ->init('blog')
            ->field(function ($builder) {
                
                $builder->id('id', 'ID');
                $builder->select('categoryId', '分类')->optionModelTree('blog_category');
                $builder->text('title', '标题')->required();
                $builder->richHtml('content', '内容')->required();
                $builder->textarea('summary', '摘要')->listable(false);
                $builder->tags('tag', '标签')
                    ->serializeType(Tags::SERIALIZE_TYPE_COLON_SEPARATED)
                    ->tagModelField('blog', 'tag', Tags::SERIALIZE_TYPE_COLON_SEPARATED);
                $builder->images('images', '图片')->listable(false);
                $builder->text('seoKeywords', 'SEO关键词')->listable(false);
                $builder->textarea('seoDescription', 'SEO描述')->listable(false);
                $builder->datetime('postTime', '发布时间')->defaultValue(date('Y-m-d H:i:s'));
                $builder->switch('isTop', '置顶')->gridEditable(true);
                $builder->switch('isHot', '热门')->gridEditable(true);
                $builder->switch('isRecommend', '推荐')->gridEditable(true);
                $builder->switch('isPublished', '发布')->optionsYesNo()->defaultValue(true);
                $builder->display('created_at', L('Created At'))->listable(false);
                $builder->display('updated_at', L('Updated At'))->listable(false);
            })
            ->gridFilter(function (GridFilter $filter) {
                $filter->eq('id', L('ID'));
                $filter->like('title', '标题');
            })
            ->hookSaving(function (Form $form) use (&$updatedCategoryIds) {
                if ($form->itemId()) {
                    $blog = ModelUtil::get('blog', $form->itemId());
                    if (!empty($blog['categoryId'])) {
                        $updatedCategoryIds[] = $blog['categoryId'];
                    }
                }
                return Response::generateSuccess();
            })
            ->hookChanged(function (Form $form) use (&$updatedCategoryIds) {
                RepositoryUtil::makeItems($form->item())->map(function ($item) use (&$updatedCategoryIds) {
                    $updatedCategoryIds[] = $item->categoryId;
                    SiteUrlProvider::update(modstart_web_url('blog/' . $item->id), $item->title, [
                        'biz' => 'blog',
                    ]);
                    var_dump($item);
                });
                if (!empty($updatedCategoryIds)) {
                    $updatedCategoryIds = array_unique($updatedCategoryIds);
                    BlogCategoryUtil::updateCount($updatedCategoryIds);
                }
                BlogTagUtil::clearCache();
            })
            ->title('博客管理');
    }
}
