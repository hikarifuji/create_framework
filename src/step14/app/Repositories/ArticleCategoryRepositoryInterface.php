<?php

declare(strict_types=1);

namespace App\Repositories;

/**
 * 記事カテゴリに関連するリポジトリのインターフェース
 * @package App\Repositories
 */
interface ArticleCategoryRepositoryInterface
{
    /**
     * 記事カテゴリを連想配列の形式で返す。戻り値は、以下のようになる。
     * [1 => 'ランチ', 2 => 'ディナー', 3 => 'ティータイム']
     */
    public function findAllAsMap(): array;

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