<?php

declare(strict_types=1);

namespace App\QueryServices;

use App\Libs\AbstractMariadbQueryService;
use PDO;

/**
 * 記事に関連するデータベース用のクエリサービス
 * @package App\Repositories
 */
class ArticleMariadbQueryService extends AbstractMariadbQueryService implements ArticleQueryServiceInterface
{
    /**
     * ArticleQueryService constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 条件に応じた複数の記事を取得する
     * @param array $conditions 絞り込み条件を表す連想配列
     * @param string $order order by句に指定するカラム名
     * @param int $limit limit句に指定する数値
     * @param int $offset offset句に指定する数値
     * @return array 検索結果のレコード配列
     */
    public function findArticles(array $conditions, ?string $order, ?int $limit, ?int $offset)
    {
        $sql = <<< SQL
SELECT articles.*, users.name as user_name FROM articles
LEFT OUTER JOIN users
ON articles.user_id = users.id
SQL;
        if (isset($conditions['category'])) {
            $sql .= ' WHERE category = :category ';
        }
        if ($order) {
            $sql .= " ORDER BY {$order} ";
        }
        if ($limit) {
            $sql .= ' LIMIT :limit ';
        }
        if ($offset) {
            $sql .= ' OFFSET :offset ';
        }
        $statement = $this->connection->prepare($sql);
        if (isset($conditions['category'])) {
            $statement->bindValue(':category', $conditions['category'], PDO::PARAM_INT);
        }
        if ($limit) {
            $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        }
        if ($offset) {
            $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        }
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 指定された記事IDに対する、コメントの配列(article_commentsレコードの配列)を返す
     * @param int $articleId 記事ID
     * @return array 記事に対するコメントの配列
     */
    public function findComments(int $articleId, ?string $order)
    {
        $sql = <<< SQL
SELECT comments.*, users.name as user_name FROM article_comments comments
LEFT OUTER JOIN users
ON comments.user_id = users.id
WHERE article_id = :article_id
SQL;
        if ($order) {
            $sql .= " ORDER BY {$order} ";
        }
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':article_id', $articleId, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 記事を人気ランキング順に取得する。
     * このメソッドではarticles#rankカラムは参照せずに、likesカラムとpvカラムのみを見て記事の人気を判断する
     * @param int $limit 取得する最大件数。たとえば、30位まで取得したいときは30を指定する
     * @return array 人気ランキング順の記事の配列。0番目に1位の記事、1番目に2位の記事...のようになる。
     */
    public function findPopularArticles(int $limit)
    {
        $sql = <<< SQL
SELECT * FROM 
(
  SELECT id, changed, coalesce(likes / pv, 0) AS point FROM articles
  WHERE likes > 0 AND pv > 0
  LIMIT :limit
) tmp
ORDER BY point DESC, changed DESC
SQL;
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 記事を人気ランキング順に取得する。
     * このメソッドではarticles#rankカラムのみを参照して記事の人気を判断する
     * @param int $limit 取得する最大件数。たとえば、30位まで取得したいときは30を指定する
     * @return array 人気ランキング順の記事の配列。0番目に1位の記事、1番目に2位の記事...のようになる。
     */
    public function findByRank(int $limit)
    {
        $sql = <<< SQL
SELECT articles.*, users.name as user_name FROM articles
LEFT OUTER JOIN users
ON articles.user_id = users.id
WHERE rank is not null
ORDER BY rank
LIMIT :limit
SQL;
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}