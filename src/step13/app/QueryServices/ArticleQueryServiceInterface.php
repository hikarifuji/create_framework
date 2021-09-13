<?php

declare(strict_types=1);

namespace App\QueryServices;

/**
 * 記事に関連するクエリサービスのインターフェース
 * @package App\Repositories
 */
interface ArticleQueryServiceInterface
{
    /**
     * 条件に応じた複数の記事を取得する
     * @param array $conditions 絞り込み条件を表す連想配列
     * @param string|null $order order by句に指定するカラム名
     * @param int|null $limit limit句に指定する数値
     * @param int|null $offset offset句に指定する数値
     * @return array 検索結果のレコード配列
     */
    public function findArticles(array $conditions, ?string $order, ?int $limit, ?int $offset);

    /**
     * 指定された記事IDに対する、コメントの配列(article_commentsレコードの配列)を返す
     * @param int $articleId 記事ID
     * @return array 記事に対するコメントの配列
     */
    public function findComments(int $articleId, ?string $order);

    /**
     * 記事を人気ランキング順に取得する。
     * このメソッドではarticles#rankカラムは参照せずに、likesカラムとpvカラムのみを見て記事の人気を判断する
     * @param int $limit 取得する最大件数。たとえば、30位まで取得したいときは30を指定する
     * @return array 人気ランキング順の記事の配列。0番目に1位の記事、1番目に2位の記事...のようになる。
     */
    public function findPopularArticles(int $limit);

    /**
     * 記事を人気ランキング順に取得する。
     * このメソッドではarticles#rankカラムのみを参照して記事の人気を判断する
     * @param int $limit 取得する最大件数。たとえば、30位まで取得したいときは30を指定する
     * @return array 人気ランキング順の記事の配列。0番目に1位の記事、1番目に2位の記事...のようになる。
     */
    public function findByRank(int $limit);
}