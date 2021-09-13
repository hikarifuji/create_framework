<?php

declare(strict_types=1);

namespace App\Libs\Core\Exception;

use Throwable;

/**
 * ページ遷移が不正であることを表す例外クラス
 * @package App\Libs\Core\Exception
 */
class ForbiddenException extends \Exception implements HttpExceptionInterface
{
    /**
     * HTTPステータスコードを返す
     */
    public function getHttpStatusCode(): array
    {
        return [403, 'Forbidden'];
    }
}