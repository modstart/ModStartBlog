<?php


namespace Module\Blog\Core;


use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Exception\BizException;
use ModStart\Core\Input\Request;
use ModStart\Core\Input\Response;
use ModStart\Core\Util\ArrayUtil;
use ModStart\Form\Form;
use Module\Blog\Model\BlogComment;
use Module\Blog\Type\BlogCommentStatus;
use Module\Vendor\Provider\ContentVerify\AbstractContentVerifyBiz;

class BlogCommentContentVerifyBiz extends AbstractContentVerifyBiz
{
    const NAME = 'Blog_Comment';
    const TITLE = '博客-评论审核';

    public function name()
    {
        return self::NAME;
    }

    public function title()
    {
        return self::TITLE;
    }

    public function formVerifyEnable()
    {
        return true;
    }

    public function verifyAutoProcess($param)
    {
        $record = ModelUtil::get(BlogComment::class, intval($param['id']));
        $pass = $this->censorRichHtmlSuccess('Blog_Censor', $record['content']);
        if ($pass) {
            ModelUtil::update(BlogComment::class, $record['id'], ['status' => BlogCommentStatus::VERIFY_SUCCESS]);
        }
        return $pass;
    }

    public function buildForm(Form $form, $param)
    {
        $record = ModelUtil::get(BlogComment::class, intval($param['id']));
        BizException::throwsIfEmpty('内容已删除', $record);
        $form->html('content', '内容')->htmlContent($record['content']);
        if ($record['status'] == BlogCommentStatus::WAIT_VERIFY) {
            $record['status'] = BlogCommentStatus::VERIFY_SUCCESS;
            $form->radio('status', '状态')->options([
                BlogCommentStatus::VERIFY_SUCCESS => '通过',
                BlogCommentStatus::VERIFY_FAIL => '拒绝',
            ]);
            if (Request::isPost()) {
                return $form->formRequest(function (Form $form) use ($record) {
                    $data = ArrayUtil::keepKeys($form->dataForming(), ['status']);
                    ModelUtil::update(BlogComment::class, $record['id'], $data);
                    return Response::redirect('[reload]');
                });
            }
        } else {
            $form->type('status', '状态')->type(BlogCommentStatus::class)->addable(true)->readonly(true);
            $form->showSubmit(false);
        }
        $form->showReset(false);
        $form->item($record)->fillFields();
    }

    public function verifyCount()
    {
        return ModelUtil::count(BlogComment::class, ['status' => BlogCommentStatus::WAIT_VERIFY]);
    }

    public function verifyRule()
    {
        return '\\Module\\Blog\\Admin\\Controller\\BlogCommentController@index';
    }

}
