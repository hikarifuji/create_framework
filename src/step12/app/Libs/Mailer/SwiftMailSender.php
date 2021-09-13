<?php

declare(strict_types=1);

namespace App\Libs\Mailer;

use App\Libs\ApplicationConfigs;
use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_Message;

/**
 * SwiftMailerを使ったメール送信クラス
 * @package App\Libs\Mailer
 */
class SwiftMailSender implements MailerInterface
{
    /**
     * @var \Swift_Mailer SwiftMailerインスタンス
     */
    private Swift_Mailer $mailer;

    /**
     * SwiftMailSender constructor.
     */
    public function __construct()
    {
        $smtp = ApplicationConfigs::getInstance()->getSmtp();
        $transport = new Swift_SmtpTransport($smtp['host'], $smtp['port'], $smtp['protocol']);
        $transport->setUsername($smtp['user']);
        $transport->setPassword($smtp['password']);
        $this->mailer = new Swift_Mailer($transport);
    }

    /**
     * メール送信する
     * @param MailerConfig $mailerConfig
     * @return int|mixed
     */
    public function send(MailerConfig $mailerConfig)
    {
        $message = new Swift_Message($mailerConfig->getSubject());
        $message->setFrom([$mailerConfig->getFromAddress() => $mailerConfig->getFromName()]);
        $message->setTo($mailerConfig->getTo());
        $message->setBody($mailerConfig->getBody());
        $result = $this->mailer->send($message);
        return $result;
    }
}