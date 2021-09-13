<?php

declare(strict_types=1);

namespace App\Modules\Common\Controllers;

use App\Libs\Core\Exception\HttpExceptionInterface;
use App\Libs\Core\Container;
use App\Libs\Core\View;
use Psr\Log\LoggerInterface;

/**
 * 例外ページ用のコントローラー
 * @package App\Libs\Core
 */
class ExceptionController
{
    /**
     * @var LoggerInterface ロガー
     */
    protected LoggerInterface $logger;

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
        $view = $this->loadView();
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
        $view->message = $message;
        echo $view->render();
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

    /**
     * エラーページ用のViewインスタンスを取得する
     */
    private function loadView(): View
    {
        $templatePath = __DIR__ . "/../Views/exception/index.html";
        $view = new View($templatePath, __DIR__ . '/../Layouts/layout-empty.html');
        return $view;
    }
}
