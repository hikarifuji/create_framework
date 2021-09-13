<?php

declare(strict_types=1);

namespace App\Libs\Core;

/**
 * リクエスト情報を表すインターフェース
 * @package App\Libs\Core
 */
interface RequestInterface
{
    /**
     * GETリクエストの値を取得する
     * @param string $key GETパラメータ名
     * @return mixed GETパラメータ値、存在しなければnull
     */
    public function byGet(string $key);

    /**
     * POSTリクエストの値を取得する
     * @param string $key POSTパラメータ名
     * @return mixed POSTパラメータ値、存在しなければnull
     */
    public function byPost(string $key);

    /**
     * GETリクエストの値を数値で取得する
     * @param string $key GETパラメータ名
     * @return int|null|bool GETパラメータ値。パラメータが存在しないか、存在するが数値でなければnull
     */
    public function byGetAsInt(string $key): ?int;

    /**
     * POSTリクエストの値を数値で取得する
     * @param string $key POSTパラメータ名
     * @return int|null POSTパラメータ値。パラメータが存在しないか、存在するが数値でなければnull
     */
    public function byPostAsInt(string $key): ?int;

    /**
     * GETパラメータ名が存在するかを調べる
     */
    public function hasGet(string $key): bool;

    /**
     * POSTパラメータ名が存在するかを調べる
     */
    public function hasPost(string $key): bool;

    /**
     * すべてのGETパラメータ名、パラメータ値を返す
     */
    public function getAllGet(): array;

    /**
     * すべてのPOSTパラメータ名、パラメータ値を返す
     */
    public function getAllPost(): array;
}
