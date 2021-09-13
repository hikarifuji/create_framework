<?php

declare(strict_types=1);

namespace App\Modules\Common\Controllers;

use App\Libs\Core\Exception\HttpExceptionInterface;
use App\Libs\Core\Container;

/**
 * 例外ページ用のコントローラー
 * @package App\Libs\Core
 */
class ExceptionController
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->logger = Container::getInstance()->get('logger');
    }

    /**
     * エラーページを表示する
     */
    public function showAction(\Throwable $e)
    {
        $message = null;
        if ($e instanceof HttpExceptionInterface) {
            list($statusCode, $statusMessage) = $e->getHttpStatusCode();
            header('HTTP/1.1 ' . $statusCode . ' ' . $statusMessage);
            if ($statusCode === 404) {
                $message = 'ページが見つかりません。(404 Page Not Found.)';
            } else {
                $message = '内部エラーが発生しました。';
                $this->writeLog($e);
            }
        } else {
            header('HTTP/1.1 500 Internal Server Error');
            $message = '内部エラーが発生しました。';
            $this->writeLog($e);
        }
        echo <<< MESSAGE
<html>
<head><meta charset="utf-8"></head>
<body>
<h2>申し訳ありません。エラーが発生しました。</h2>
<p>メッセージ：{$message}</p>
<p>
問題が解決しないときは、以下にお問い合わせください。<br>
●☓▲株式会社　コンタクトセンター<br>
TEL：***-***-***
</p>
</body>
MESSAGE;
        exit;
    }

    /**
     * 例外をログ出力する
     */
    private function writeLog(\Throwable $e)
    {
        $this->logger->error($e->getMessage());
        $this->logger->error(print_r($e->getTraceAsString(), true));
    }

}
