<?php

namespace Module\EmailSmtp\Admin\Controller;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\View;
use ModStart\Admin\Layout\AdminConfigBuilder;
use ModStart\Core\Input\Response;
use ModStart\Form\Form;
use ModStart\Widget\Box;
use ModStart\Widget\ButtonDialogRequest;
use Module\EmailSmtp\Provider\SmtpMailSenderProvider;

class   ConfigController extends Controller
{
    public static $PermitMethodMap = [
        'test' => 'setting',
    ];

    public function setting(AdminConfigBuilder $builder)
    {
        $builder->pageTitle('SMTP邮箱');
        $builder->switch('systemEmailEnable', '开启SMTP邮件发送');
        $builder->text('systemEmailSmtpServer', 'SMTP服务器地址');
        $builder->switch('systemEmailSmtpSsl', 'SMTP是否为SSL');
        $builder->text('systemEmailSmtpUser', 'SMTP用户');
        $builder->text('systemEmailSmtpPassword', 'SMTP密码');
        $builder->text('EmailSmtp_FromName', '邮件来源名称');
        $builder->text('EmailSmtp_FromEmail', '邮件来源邮箱')
            ->help('如果不填写则使用SMTP用户作为来源邮箱');
        $builder->formClass('wide');
        $builder->hookFormWrap(function (Form $form) {
            return Box::make($form, 'SMTP邮箱')
                ->tool(
                    ButtonDialogRequest::make('default',
                        '测试发送',
                        modstart_admin_url('email_smtp/config/test')
                    ));
        });
        return $builder->perform();
    }

    public function test(AdminConfigBuilder $builder)
    {
        $builder->useDialog();
        $builder->pageTitle('邮件发送测试');
        $builder->disableBoxWrap(true);
        $builder->text('email', '测试接收邮箱')->rules('required');
        $builder->formClass('wide');
        return $builder->perform(null, function (Form $form) {
            $data = $form->dataForming();
            $email = $data['email'];
            $sender = new SmtpMailSenderProvider();
            $content = View::make('module::EmailSmtp.View.mail.test', [])->render();
            $ret = $sender->send(
                $email,
                $email,
                '测试邮件',
                $content,
                []
            );
            if (Response::isError($ret)) {
                return Response::generateError('邮件发送失败：' . $ret['msg']);
            }
            return Response::generateSuccess('测试邮件成功发送到' . $email);
        });
    }


}
