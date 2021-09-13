<?php

declare(strict_types=1);

namespace App\Libs\Core;

/**
 * ビュー(主にHTML)を読み込み、レンダリングするためのクラス
 * @package App\Libs\Core
 */
class View
{
    /**
     * @var array ビュー変数の連想配列
     */
    private array $datas;

    /**
     * @var string テンプレートファイル(主にHTML)のパス
     */
    private string $templatePath;

    /**
     * @var string レイアウトファイルのパス
     */
    private string $layoutPath;

    /**
     * View constructor.
     * @param string $templatePath テンプレートファイルのパス
     * @param string $layoutPath レイアウトファイルのパス
     */
    public function __construct(string $templatePath, string $layoutPath)
    {
        $this->datas = [];
        $this->templatePath = $templatePath;
        $this->layoutPath = $layoutPath;
    }

    /**
     * ビュー変数をセットしたテンプレートファイルの内容を返す。
     * 本メソッドでは出力は行わないため、呼び出し元で「echo $view->render();」のようにすること。
     * @return string
     */
    public function render(): string
    {
        ob_start();
        include($this->layoutPath);
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    /**
     * ビュー変数をセットする
     * @param $name ビュー変数名
     * @param $value ビュー変数値
     */
    public function __set(string $name, mixed $value)
    {
        $this->datas[$name] = $value;
    }

    /**
     * ビュー変数を取得する
     * @param $name ビュー変数名
     * @return |null ビュー変数値
     */
    public function __get(string $name)
    {
        if (isset($this->datas[$name])) {
            return $this->datas[$name];
        } else {
            return null;
        }
    }

    /**
     * テンプレートファイルの中で$this->escape($value)のような形式でメソッドを呼び出したときに、自動的にコールされるマジックメソッド。
     * この例では$helperMethodに'escape'が、$argsに$valueがセットされる。
     * 結果として、App\Helpers\EscapeHelper.phpクラスのescapeメソッドが実行される。
     * escapeメソッドの引数には$valueの値がセットされる。
     * @param $helperMethod
     * @param $args
     * @return mixed
     */
    public function __call($helperMethod, $args)
    {
        $helperClass = "\\App\\Helpers\\" . ucfirst($helperMethod) . 'Helper';
        $helperInstance = new $helperClass();
        return call_user_func_array(
            array($helperInstance, $helperMethod),
            $args
        );
    }
}
