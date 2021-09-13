<?php

declare(strict_types=1);

namespace App\Libs\Core;

/**
 * リクエスト情報を表すクラス
 * @package App\Libs\Core
 */
class Request implements RequestInterface
{
    /**
     * GETリクエストの値を取得する
     * @param string $key GETパラメータ名
     * @return mixed GETパラメータ値、存在しなければnull
     */
    public function byGet(string $key)
    {
        return filter_input(INPUT_GET, $key);
    }

    /**
     * POSTリクエストの値を取得する
     * @param string $key POSTパラメータ名
     * @return mixed POSTパラメータ値、存在しなければnull
     */
    public function byPost(string $key)
    {
        return filter_input(INPUT_POST, $key);
    }

    /**
     * GETリクエストの値を数値で取得する
     * @param string $key GETパラメータ名
     * @return int|null|bool GETパラメータ値。パラメータが存在しないか、存在するが数値でなければnull
     */
    public function byGetAsInt(string $key): ?int
    {
        $input = filter_input(INPUT_GET, $key, FILTER_VALIDATE_INT);
        if ($input === false) {
            return null;
        }
        return $input;
    }

    /**
     * POSTリクエストの値を数値で取得する
     * @param string $key POSTパラメータ名
     * @return int|null POSTパラメータ値。パラメータが存在しないか、存在するが数値でなければnull
     */
    public function byPostAsInt(string $key): ?int
    {
        $input = filter_input(INPUT_POST, $key, FILTER_VALIDATE_INT);
        if ($input === false) {
            return null;
        }
        return $input;
    }

    /**
     * GETパラメータ名が存在するかを調べる
     */
    public function hasGet(string $key): bool
    {
        return isset($_GET[$key]);
    }

    /**
     * POSTパラメータ名が存在するかを調べる
     */
    public function hasPost(string $key): bool
    {
        return isset($_POST[$key]);
    }

    /**
     * すべてのGETパラメータ名、パラメータ値を返す
     */
    public function getAllGet(): array
    {
        return $_GET;
    }

    /**
     * すべてのPOSTパラメータ名、パラメータ値を返す
     */
    public function getAllPost(): array
    {
        return $_POST;
    }
}
