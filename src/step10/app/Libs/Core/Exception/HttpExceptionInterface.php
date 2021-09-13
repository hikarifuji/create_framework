<?php

declare(strict_types=1);

namespace App\Libs\Core\Exception;

/**
 * 「200」以外のHTTPレスポンスステータスを返すために使う例外インターフェース
 */
interface HttpExceptionInterface
{
    /**
     * HTTPステータスコードを返す
     */
    public function getHttpStatusCode(): array;
}