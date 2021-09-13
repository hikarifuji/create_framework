<?php

declare(strict_types=1);

namespace App\Repositories;

/**
 * 記事に関連するリポジトリのインターフェース
 * @package App\Repositories
 */
interface ArticleRepositoryInterface
{
    /**
     * 記事ID指定で、1件のみ、レコードを取得する
     * @param int $id 記事ID
     * @return array 記事レコード
     */
    public function findOneById(int $id);

    /**
     * 条件に応じた記事の件数を取得する
     * @param array $conditions 絞り込み条件を表す連想配列
     * @return int 検索結果の件数
     */
    public function getCount(array $conditions): int;

    /**
     * 記事に対する、おいしイイね数を1カウントアップする
     * @param int $articleId 記事ID
     */
    public function countUpLike(int $articleId): void;

    /**
     * 記事に対する、PV数を1カウントアップする
     * @param int $articleId 記事ID
     */
    public function countUpPv(int $articleId): void;

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