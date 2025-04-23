<?php


namespace Module\NotifierEmail\Driver;


use ModStart\Core\Util\FormatUtil;
use Module\Vendor\Job\MailSendJob;
use Module\Vendor\Provider\Notifier\AbstractNotifierProvider;

class EmailNotifierProvider extends AbstractNotifierProvider
{
    public function name()
    {
        return 'Email';
    }

    public function title()
    {
        return '邮件通知';
    }

    public function notify($biz, $title, $content, $param = [])
    {
        $emails = modstart_config('NotifierEmail_Biz_' . $biz, '');
        if (empty($emails)) {
            $emails = modstart_config('NotifierEmail_DefaultEmail', '');
        }
        foreach (explode(',', trim($emails)) as $email) {
            if (!FormatUtil::isEmail($email)) {
                continue;
            }
            MailSendJob::create($email, $title, 'module::NotifierEmail.View.mail.notify', [
                'title' => $title,
                'content' => $content,
                'param' => $param,
            ], null, []);
        }
    }
}
