<?php

declare(strict_types=1);

namespace App\Libs\Core\Traits;

trait SingletonTrait
{
    /**
     * 自身のインスタンス
     */
    private static ?object $instance = null;

    /**
     * 自身のインスタンスを取得する
     */
    final public static function getInstance(): object
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * コンストラクタ
     */
    final private function __construct()
    {
        $this->initialize();
    }

    /**
     * クラスの初期化処理。
     * 初期化処理が必要なときは、トレイトの呼び出し側のクラスで本メソッドを再定義すること。
     */
    public function initialize()
    {
        ;
    }
}
