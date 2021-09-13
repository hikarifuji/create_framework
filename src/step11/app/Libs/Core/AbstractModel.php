<?php

declare(strict_types=1);

namespace App\Libs\Core;

use Psr\Log\LoggerInterface;

/**
 * 汎用的なモデルクラス
 * @package App\Libs\Core
 */
abstract class AbstractModel
{
    /**
     * @var LoggerInterface ロガー
     */
    protected LoggerInterface $logger;

    /**
     * AbstractModel constructor.
     */
    public function __construct()
    {
        $this->logger = Container::getInstance()->get('logger');
    }
}