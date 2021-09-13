<?php

declare(strict_types=1);

namespace App\Libs\Core;

use Psr\Log\LoggerInterface;

require_once __DIR__ . '/../../../vendor/autoload.php';

/**
 * コンソールプログラムのための抽象クラス
 * @package App\Libs\Core
 */
abstract class ConsoleApplication
{
    /**
     * @var LoggerInterface ロガー
     */
    protected LoggerInterface $logger;

    /**
     * ConsoleApplication constructor.
     */
    public function __construct()
    {
        $container = require __DIR__ . '/../../Core/container.php';
        $container();
        $this->logger = Container::getInstance()->get('logger');
    }

    /**
     * コンソールプログラムを実行する
     */
    public final function execute(): void
    {
        $this->start();
        try {
            $this->job();
        } catch (Exception $e) {
            $this->logger->error(print_r($e->getMessage(), true));
            $this->logger->error(print_r($e->getTrace()[0], true));
        }
        $this->end();
    }

    /**
     * 初期化処理。必要に応じてオーバーライドすること
     */
    protected function start(): void
    {
        ;
    }

    /**
     * 終了処理。必要に応じてオーバーライドすること
     */
    protected function end(): void
    {
        ;
    }

    /**
     * 本処理。
     */
    abstract protected function job(): void;
}