<?php

declare(strict_types=1);

namespace App\Libs\Core\Exception;

/**
 * ページが見つからなかったことを表す例外クラス
 * @package App\Libs\Core\Exception
 */
class PageNotFoundException extends \Exception implements HttpExceptionInterface
{
    /**
     * HTTPステータスコードを返す
     */
    public function getHttpStatusCode(): array
    {
        return [404, 'Not Found'];
    }
}