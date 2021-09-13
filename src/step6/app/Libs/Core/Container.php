<?php

declare(strict_types=1);

namespace App\Libs\Core;

use App\Libs\Core\Traits\SingletonTrait;
use Pimple\Container as PimpleContainer;

/**
 * DIコンテナであるPimple\Containerのラッパークラス
 * @package App\Libs\Core
 */
class Container
{
    /*
     * シングルトンクラスのためのトレイトを使う
     */
    use SingletonTrait;

    /**
     * @var PimpleContainer Pimpleコンテナインスタンス
     */
    private PimpleContainer $pimpleContainer;

    /**
     * クラスの初期化処理
     */
    public function initialize()
    {
        $this->pimpleContainer = new PimpleContainer();
    }

    /**
     * コンテナから値を取得する
     * @param string $name
     * @return mixed
     */
    public function get(string $name)
    {
        return $this->pimpleContainer[$name];
    }

    /**
     * コンテナに値をセットする
     * @param string $name
     * @param $value
     */
    public function set(string $name, $value)
    {
        $this->pimpleContainer[$name] = $value;
    }
}
