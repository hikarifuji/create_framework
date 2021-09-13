<?php

declare(strict_types=1);

namespace App\Libs\Mailer;

use App\Libs\ApplicationConfigs;

/**
 * デバッグ用のメール送信クラス。
 * 実際のメール送信は行わず、logs/mail-YYYYMMDD.logへの出力のみを行う。
 * @package App\Libs\Mailer
 */
class DebugMailSender implements MailerInterface
{
    /**
     * DebugMailSender constructor.
     */
    public function __construct()
    {
    }

    /**
     * メール送信(実際にはログ出力のみ)を行う
     * @param MailerConfig $mailerConfig
     */
    public function send(MailerConfig $mailerConfig)
    {
        $logDir = ApplicationConfigs::getInstance()->getPaths()['log'];
        file_put_contents($logDir . '/' . 'mail-' . date('Ymd') . '.log', print_r($mailerConfig, true), FILE_APPEND);
    }
}