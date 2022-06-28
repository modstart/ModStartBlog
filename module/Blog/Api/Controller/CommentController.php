<?php


namespace Module\Blog\Api\Controller;


use Illuminate\Routing\Controller;
use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Exception\BizException;
use ModStart\Core\Input\InputPackage;
use ModStart\Core\Input\Response;
use ModStart\Core\Util\HtmlUtil;
use Module\Blog\Type\BlogCommentStatus;
use Module\Member\Auth\MemberUser;
use Module\Vendor\Provider\Captcha\CaptchaProvider;


class CommentController extends Controller
{
    
    public function add()
    {
        $input = InputPackage::buildFromInput();
        $blogId = $input->getInteger('blogId');
        $blog = ModelUtil::get('blog', $blogId);
        BizException::throwsIfEmpty('博客不存在', $blog);
        $data = [];
        $data['blogId'] = $blogId;
        $data['username'] = $input->getTrimString('username');
        $data['content'] = $input->getTrimString('content');
        $data['email'] = $input->getTrimString('email');
        $data['url'] = $input->getTrimString('url');
        $ret = CaptchaProvider::get(modstart_config('Blog_BlogCaptchaProvider', 'default'))->validate();
        if (Response::isError($ret)) {
            return $ret;
        }
        BizException::throwsIfEmpty('内容为空', $data['content']);
        $data['content'] = HtmlUtil::text2html($data['content']);
        if (modstart_module_enabled('Member')) {
            if (MemberUser::isLogin()) {
                $data['username'] = MemberUser::get('username');
            }
            $data['memberUserId'] = MemberUser::id();
        } else {
            $data['memberUserId'] = 0;
        }
        $data['status'] = BlogCommentStatus::WAIT_VERIFY;
        ModelUtil::insert('blog_comment', $data);
        ModelUtil::update('blog', $blogId, [
            'commentCount' => ModelUtil::count('blog_comment', [
                'blogId' => $blogId,
                'status' => BlogCommentStatus::VERIFY_SUCCESS,
            ])
        ]);
        return Response::generate(0, '提交成功，后台审核后将会显示', null, '[reload]');
    }
}
