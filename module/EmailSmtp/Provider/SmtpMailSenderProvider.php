<?php


namespace Module\EmailSmtp\Provider;


use Illuminate\Support\Facades\Mail;
use ModStart\Core\Exception\BizException;
use ModStart\Core\Input\Response;
use ModStart\ModStart;
use Module\Vendor\Provider\MailSender\AbstractMailSenderProvider;

class SmtpMailSenderProvider extends AbstractMailSenderProvider
{
    const NAME = 'smtp';

    /**
     * SmtpMailSenderProvider constructor.
     */
    public function __construct()
    {
        $config = modstart_config();
        $fromName = modstart_config('EmailSmtp_FromName', '');
        if (empty($fromName)) {
            $fromName = $config->getWithEnv('siteName') . ' @ ' . $config->getWithEnv('siteDomain');
        }
        $fromUser = modstart_config('EmailSmtp_FromEmail', '');
        if (empty($fromUser)) {
            $fromUser = $config->getWithEnv('systemEmailSmtpUser');
        }
        config([
            'mail' => [
                'driver' => 'smtp',
                'host' => $config->getWithEnv('systemEmailSmtpServer'),
                'port' => $config->getWithEnv('systemEmailSmtpSsl', false) ? 465 : 25,
                'encryption' => $config->getWithEnv('systemEmailSmtpSsl', false) ? 'ssl' : 'tls',
                'from' => [
                    'address' => $fromUser,
                    'name' => $fromName
                ],
                'username' => $config->getWithEnv('systemEmailSmtpUser'),
                'password' => $config->getWithEnv('systemEmailSmtpPassword'),
                'timeout' => 10,
            ]
        ]);
    }

    public function name()
    {
        return 'smtp';
    }


    public function send($email, $emailUserName, $subject, $content, $param = [])
    {
        try {
            $sent = Mail::send([], [], function ($message) use ($email, $emailUserName, $subject, $content) {
                if (ModStart::env() == 'laravel5') {
                    /** @var \Swift_Message $message */
                    $message
                        ->setTo($email, $emailUserName)
                        ->setFrom([
                            config('mail.from.address') => config('mail.from.name')
                        ])
                        ->setBody($content, 'text/html')
                        ->setSubject($subject);
                } else {
                    /** @var \Illuminate\Mail\Message */
                    $message->to($email, $emailUserName)
                        ->subject($subject)
                        ->html($content);
                }

                //            if (!empty($param['attachment'])) {
                //                foreach ($param['attachment'] as $filename => $path) {
                //                    $message->attach($path, ['as' => $filename]);
                //                }
                //            }
            });
            if (ModStart::env() == 'laravel5') {
                BizException::throwsIf('邮件未发送', $sent < 1);
            } else {
                /** @var \Symfony\Component\Mailer\SentMessage $sent */
                BizException::throwsIf('邮件未发送', !$sent->getMessageId());
            }
            return Response::generateSuccess();
        } catch (\Exception $e) {
            return Response::generateError('ERROR:' . $e->getMessage());
        }
    }

}
