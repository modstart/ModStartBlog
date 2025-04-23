<?php


namespace Module\Blog\Core;


use ModStart\Core\Dao\ModelUtil;
use ModStart\Core\Exception\BizException;
use ModStart\Core\Input\Request;
use ModStart\Core\Input\Response;
use ModStart\Core\Util\ArrayUtil;
use ModStart\Form\Form;
use Module\Blog\Model\BlogMessage;
use Module\Blog\Type\BlogMessageStatus;
use Module\Vendor\Provider\ContentVerify\AbstractContentVerifyBiz;

class BlogMessageContentVerifyBiz extends AbstractContentVerifyBiz
{
    const NAME = 'Blog_Message';
    const TITLE = '博客-留言审核';

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
        $record = ModelUtil::get(BlogMessage::class, intval($param['id']));
        $pass = $this->censorRichHtmlSuccess('Blog_Censor', $record['content']);
        if ($pass) {
            ModelUtil::update(BlogMessage::class, $record['id'], ['status' => BlogMessageStatus::VERIFY_SUCCESS]);
        }
        return $pass;
    }

    public function buildForm(Form $form, $param)
    {
        $record = ModelUtil::get(BlogMessage::class, intval($param['id']));
        BizException::throwsIfEmpty('内容已删除', $record);
        $form->html('content', '内容')->htmlContent($record['content']);
        if ($record['status'] == BlogMessageStatus::WAIT_VERIFY) {
            $record['status'] = BlogMessageStatus::VERIFY_SUCCESS;
            $form->radio('status', '状态')->options([
                BlogMessageStatus::VERIFY_SUCCESS => '通过',
                BlogMessageStatus::VERIFY_FAIL => '拒绝',
            ]);
            if (Request::isPost()) {
                return $form->formRequest(function (Form $form) use ($record) {
                    $data = ArrayUtil::keepKeys($form->dataForming(), ['status']);
                    ModelUtil::update(BlogMessage::class, $record['id'], $data);
                    return Response::redirect('[reload]');
                });
            }
        } else {
            $form->type('status', '状态')->type(BlogMessageStatus::class)->addable(true)->readonly(true);
            $form->showSubmit(false);
        }
        $form->showReset(false);
        $form->item($record)->fillFields();
    }

    public function verifyCount()
    {
        return ModelUtil::count(BlogMessage::class, ['status' => BlogMessageStatus::WAIT_VERIFY]);
    }

    public function verifyRule()
    {
        return '\\Module\\Blog\\Admin\\Controller\\BlogMessageController@index';
    }

}
