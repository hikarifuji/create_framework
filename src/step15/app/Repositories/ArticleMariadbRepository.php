<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Libs\AbstractMariadbRepository;
use PDO;

/**
 * 記事に関連するデータベース用のリポジトリクラス
 * @package App\Repositories
 */
class ArticleMariadbRepository extends AbstractMariadbRepository implements ArticleRepositoryInterface
{
    /**
     * ArticleRepository constructor.
     */
    public function __construct()
    {
        parent::__construct('articles');
    }

    /**
     * 記事ID指定で、1件のみ、レコードを取得する
     * @param int $id 記事ID
     * @return array 記事レコード
     */
    public function findOneById(int $id)
    {
        $statement = $this->connection->prepare("SELECT * FROM articles WHERE id = :id");
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 条件に応じた記事の件数を取得する
     * @param array $conditions 絞り込み条件を表す連想配列
     * @return int 検索結果の件数
     */
    public function getCount(array $conditions): int
    {
        $sql = 'SELECT count(*) as cnt FROM articles ';
        if (isset($conditions['category'])) {
            $sql .= ' WHERE category = :category ';
        }
        $statement = $this->connection->prepare($sql);
        if (isset($conditions['category'])) {
            $statement->bindValue(':category', $conditions['category'], PDO::PARAM_INT);
        }
        $statement->execute();
        return intval($statement->fetch(PDO::FETCH_ASSOC)['cnt']);
    }

    /**
     * 記事に対する、おいしイイね数を1カウントアップする
     * @param int $articleId 記事ID
     */
    public function countUpLike(int $articleId): void
    {
        if (intval($articleId) <= 0) {
            return;
        }
        $statement = $this->connection->prepare('UPDATE articles set likes = likes + 1 WHERE id = :id');
        $statement->bindValue(':id', $articleId, PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * 記事に対する、PV数を1カウントアップする
     * @param int $articleId 記事ID
     */
    public function countUpPv(int $articleId): void
    {
        if (intval($articleId) <= 0) {
            return;
        }
        $statement = $this->connection->prepare('UPDATE articles set pv = pv + 1 WHERE id = :id');
        $statement->bindValue(':id', $articleId, PDO::PARAM_INT);
        $statement->execute();
    }
}