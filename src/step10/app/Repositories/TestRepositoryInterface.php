<?php

declare(strict_types=1);

namespace App\Repositories;

/**
 * テスト用リポジトリのインターフェース
 * @package App\Repositories
 */
interface TestRepositoryInterface
{
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
     * @return int 挿入されたレコードのid値
     */
    public function create(array $datas): int;
}