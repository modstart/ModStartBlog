<?php


namespace Module\Blog\Admin\Controller;


use Illuminate\Routing\Controller;
use ModStart\Admin\Auth\AdminPermission;
use ModStart\Admin\Concern\HasAdminQuickCRUD;
use ModStart\Admin\Layout\AdminCRUDBuilder;
use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Input\Response;
use ModStart\Core\Util\CRUDUtil;
use ModStart\Grid\Displayer\ItemOperate;
use ModStart\Grid\GridFilter;
use ModStart\Support\Concern\HasFields;
use ModStart\Widget\TextAjaxRequest;
use Module\Blog\Type\BlogCommentStatus;
use Module\Blog\Type\BlogMessageStatus;

class BlogMessageController extends Controller
{
    use HasAdminQuickCRUD;

    protected function crud(AdminCRUDBuilder $builder)
    {
        $builder
            ->init('blog_message')
            ->field(function ($builder) {
                
                $builder->id('id', 'ID');
                $builder->display('created_at', L('Created At'))->listable(true);
                $builder->type('status', '状态')->type(BlogMessageStatus::class);
                $builder->display('username', '用户')->editable(false);
                $builder->display('email', '邮箱')->editable(false);
                $builder->display('url', 'URL')->editable(false);
                $builder->richHtml('content', '内容')->editable(false)->listable(true);
                $builder->richHtml('reply', '回复内容')->listable(true);
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
            ->title('博客留言')->canAdd(false)->canEdit(true);
    }

    public function verifyPass()
    {
        AdminPermission::demoCheck();
        ModelUtil::update('blog_message', CRUDUtil::id(), ['status' => BlogMessageStatus::VERIFY_SUCCESS]);
        return Response::json(0, null, null, CRUDUtil::jsGridRefresh());
    }

    public function verifyReject()
    {
        AdminPermission::demoCheck();
        ModelUtil::update('blog_message', CRUDUtil::id(), ['status' => BlogMessageStatus::VERIFY_FAIL]);
        return Response::json(0, null, null, CRUDUtil::jsGridRefresh());
    }
}
