<?php

declare(strict_types=1);

namespace App\Libs\Core\Exception;

/**
 * HTTPメソッド(GET, POSTなど)が許可されていないことを表す例外クラス
 * @package App\Libs\Core\Exception
 */
class MethodNotAllowedException extends \Exception implements HttpExceptionInterface
{
    /**
     * HTTPステータスコードを返す
     */
    public function getHttpStatusCode(): array
    {
        return [405, 'Method Not Allowed'];
    }
}