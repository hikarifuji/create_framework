<?php

declare(strict_types=1);

namespace App\Libs\Core;

use Psr\Log\LoggerInterface;

/**
 * 汎用的なコントローラークラス
 * @package App\Libs\Core
 */
abstract class AbstractController
{
    /**
     * @var LoggerInterface ロガー
     */
    protected LoggerInterface $logger;

    /**
     * @var RequestInterface HTTPリクエスト
     */
    protected RequestInterface $request;

    /**
     * AbstractController constructor.
     */
    public function __construct()
    {
        $this->logger = Container::getInstance()->get('logger');
        $this->request = Container::getInstance()->get('request');
    }

    /**
     * 別URLに遷移する
     * @param int $responseCode HTTPレスポンスコード。30xを指定すること
     * @param string $location 遷移先URL
     */
    protected function forwardTo(int $responseCode, string $location): void
    {
        $responseCodes = [
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            307 => 'Temporary Redirect',
            308 => 'Permanent Redirect', // 308を使うとWebクライアントのキャッシュに残るため注意すること
        ];
        if (!isset($responseCodes[$responseCode])) {
            return;
        }
        header("HTTP/1.1 {$responseCode} {$responseCodes[$responseCode]}");
        header("Location: {$location}");
    }
}