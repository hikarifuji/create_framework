<?php

declare(strict_types=1);

namespace App\Libs\Core\Exception;

use Throwable;

/**
 * 内部エラーを表す例外クラス
 * @package App\Libs\Core\Exception
 */
class InternalServerErrorException extends \Exception implements HttpExceptionInterface
{
    /**
     * HTTPステータスコードを返す
     */
    public function getHttpStatusCode(): array
    {
        return [500, 'Internal Server Error'];
    }
}