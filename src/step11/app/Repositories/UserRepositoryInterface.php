<?php

declare(strict_types=1);

namespace App\Repositories;

/**
 * ユーザに関連するリポジトリのインターフェース
 * @package App\Repositories
 */
interface UserRepositoryInterface
{
    /**
     * レコードを1件取得する
     */
    public function findOne(array $conditions);

    /**
     * レコードを1件、更新する
     * @param int $id idカラム値
     * @param array $datas カラム名とカラム値からなる連想配列
     */
    public function updateOne(int $id, array $datas): void;

    /**
     * レコードを全件更新する
     * @param array $datas カラム名とカラム値からなる連想配列
     */
    public function updateAll(array $datas): void;

    /**
     * レコードを挿入する
     * @param array $datas カラム名とカラム値からなる連想配列
     * @return string 挿入されたレコードのid値
     */
    public function create(array $datas): int;
}