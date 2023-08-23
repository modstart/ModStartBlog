<?php


namespace Module\Blog\Admin\Controller;


use Illuminate\Routing\Controller;
use ModStart\Admin\Auth\AdminPermission;
use ModStart\Admin\Concern\HasAdminQuickCRUD;
use ModStart\Admin\Layout\AdminCRUDBuilder;
use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Exception\BizException;
use ModStart\Core\Input\Response;
use ModStart\Core\Util\CRUDUtil;
use ModStart\Grid\Displayer\ItemOperate;
use ModStart\Grid\GridFilter;
use ModStart\Support\Concern\HasFields;
use ModStart\Widget\TextAjaxRequest;
use Module\Blog\Type\BlogCommentStatus;

class BlogCommentController extends Controller
{
    use HasAdminQuickCRUD;

    protected function crud(AdminCRUDBuilder $builder)
    {
        $builder
            ->init('blog_comment')
            ->field(function ($builder) {
                /** @var HasFields $builder */
                $builder->id('id', 'ID');
                $builder->display('created_at', L('Created At'))->listable(true);
                $builder->display('blogId', '博客');
                $builder->type('status', '状态')->type(BlogCommentStatus::class);
                $builder->display('username', '用户');
                $builder->display('email', '邮箱');
                $builder->display('url', 'URL');
                $builder->richHtml('content', '内容')->listable(true);
            })
            ->gridFilter(function (GridFilter $filter) {
                $filter->eq('id', L('ID'));
                $filter->like('username', '用户');
            })
            ->hookItemOperateRendering(function (ItemOperate $itemOperate) {
                if ($itemOperate->item()->status === BlogCommentStatus::WAIT_VERIFY) {
                    $itemOperate->prepend(TextAjaxRequest::success('审核通过', action('\\' . __CLASS__ . '@verifyPass', ['_id' => $itemOperate->item()->id])));
                    $itemOperate->prepend(TextAjaxRequest::danger('审核拒绝', action('\\' . __CLASS__ . '@verifyReject', ['_id' => $itemOperate->item()->id])));
                }
            })
            ->canBatchDelete(true)
            ->title('博客评论')->canAdd(false)->canEdit(false);
    }

    public function verifyPass()
    {
        AdminPermission::demoCheck();
        $comment = ModelUtil::get('blog_comment', CRUDUtil::id());
        BizException::throwsIfEmpty('记录不存在', $comment);
        ModelUtil::update('blog_comment', CRUDUtil::id(), ['status' => BlogCommentStatus::VERIFY_SUCCESS]);
        ModelUtil::update('blog', $comment['blogId'], [
            'commentCount' => ModelUtil::count('blog_comment', [
                'blogId' => $comment['blogId'],
                'status' => BlogCommentStatus::VERIFY_SUCCESS,
            ])
        ]);
        return Response::json(0, null, null, CRUDUtil::jsGridRefresh());
    }

    public function verifyReject()
    {
        AdminPermission::demoCheck();
        ModelUtil::update('blog_comment', CRUDUtil::id(), ['status' => BlogCommentStatus::VERIFY_FAIL]);
        return Response::json(0, null, null, CRUDUtil::jsGridRefresh());
    }
}
