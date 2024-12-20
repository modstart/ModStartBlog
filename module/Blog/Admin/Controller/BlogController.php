<?php


namespace Module\Blog\Admin\Controller;


use Illuminate\Routing\Controller;
use ModStart\Admin\Concern\HasAdminQuickCRUD;
use ModStart\Admin\Layout\AdminCRUDBuilder;
use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Input\InputPackage;
use ModStart\Core\Input\Request;
use ModStart\Core\Input\Response;
use ModStart\Core\Util\CRUDUtil;
use ModStart\Field\AbstractField;
use ModStart\Field\Tags;
use ModStart\Field\Type\FieldRenderMode;
use ModStart\Form\Form;
use ModStart\Grid\GridFilter;
use ModStart\Repository\RepositoryUtil;
use ModStart\Support\Concern\HasFields;
use ModStart\Widget\TextLink;
use Module\Blog\Core\BlogSiteUrlBiz;
use Module\Blog\Core\BlogSuperSearchBiz;
use Module\Blog\Core\BlogTagManagerBiz;
use Module\Blog\Type\BlogVisitMode;
use Module\Blog\Util\BlogCategoryUtil;
use Module\Blog\Util\BlogTagUtil;
use Module\Blog\Util\UrlUtil;
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
                /** @var HasFields $builder */
                $builder->id('id', 'ID');
                $builder->select('categoryId', '分类')->optionModelTree('blog_category');
                $builder->text('title', '标题')
                    ->hookRendering(function (AbstractField $field, $item, $index) {
                        switch ($field->renderMode()) {
                            case FieldRenderMode::GRID:
                            case FieldRenderMode::DETAIL:
                                return TextLink::primary(htmlspecialchars($item->title), UrlUtil::blog($item), 'target="_blank"');
                        }
                    })->required();
                $builder->richHtml('content', '内容')->required();
                $builder->textarea('summary', '摘要')->listable(false);
                $builder->tags('tag', '标签')
                    ->serializeType(Tags::SERIALIZE_TYPE_COLON_SEPARATED)
                    ->tagModelField('blog', 'tag', Tags::SERIALIZE_TYPE_COLON_SEPARATED);
                $builder->images('images', '图片')->listable(false);
                $builder->text('seoKeywords', 'SEO关键词')->listable(false);
                $builder->textarea('seoDescription', 'SEO描述')->listable(false);
                $builder->switch('isTop', '置顶')->gridEditable(true);
                $builder->switch('isHot', '热门')->gridEditable(true);
                $builder->switch('isRecommend', '推荐')->gridEditable(true);
                $builder->switch('isPublished', '立即发布')
                    ->optionsYesNo()
                    ->defaultValue(true)
                    ->when('=', false, function ($builder) {
                        /** @var HasFields $builder */
                        $builder->datetime('postTime', '定时发布')
                            ->defaultValue(date('Y-m-d H:i:s'));
                    });
                $builder->radio('visitMode', '访问模式')
                    ->optionType(BlogVisitMode::class)
                    ->defaultValue(BlogVisitMode::OPEN)
                    ->when('=', BlogVisitMode::PASSWORD, function ($builder) {
                        /** @var HasFields $builder */
                        $builder->text('visitPassword', '访问密码');
                    });
                $builder->datetime('created_at', '发布时间')
                    ->listable(false)
                    ->defaultValue(date('Y-m-d H:i:s'));
                $builder->display('updated_at', L('Updated At'))->listable(false);
            })
            ->hookResponse(function (Form $form) {
                if ($form->isModeAdd()) {
                    $input = InputPackage::buildFromInput();
                    if ('front' == $input->getTrimString('from')) {
                        return Response::generate(0, '发布成功', null, CRUDUtil::jsDialogCloseAndParentRefresh());
                    }
                }
            })
            ->gridFilter(function (GridFilter $filter) {
                $filter->eq('id', L('ID'));
                $filter->like('title', '标题');
                $filter->eq('isTop', '置顶')->autoHide(true)->switchRadioYesNo();
                $filter->eq('isHot', '热门')->autoHide(true)->switchRadioYesNo();
                $filter->eq('isRecommend', '推荐')->autoHide(true)->switchRadioYesNo();
                $filter->eq('isPublished', '发布')->autoHide(true)->switchRadioYesNo();
            })
            ->pageJumpEnable(true)
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
                $tags = [];
                RepositoryUtil::makeItems($form->item())->map(function ($item) use (&$updatedCategoryIds, &$tags) {
                    $updatedCategoryIds[] = $item->categoryId;
                    SiteUrlProvider::updateBiz(BlogSiteUrlBiz::NAME, modstart_web_url('blog/' . $item->id), $item->title);
                    BlogSuperSearchBiz::syncUpsert([$item->toArray()]);
                    $tags[] = $item->tag;
                });
                if (!empty($updatedCategoryIds)) {
                    $updatedCategoryIds = array_unique($updatedCategoryIds);
                    BlogCategoryUtil::updateCount($updatedCategoryIds);
                }
                BlogTagUtil::clearCache();
            })
            ->hookDeleted(function (Form $form) {
                $form->item()->each(function ($item) {
                    SiteUrlProvider::delete(modstart_web_url('blog/' . $item->id));
                    BlogSuperSearchBiz::syncDelete($item->id);
                });
            })
            ->title('博客管理');
    }
}
