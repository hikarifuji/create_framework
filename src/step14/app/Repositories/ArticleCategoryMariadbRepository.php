<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Libs\AbstractMariadbRepository;
use PDO;

/**
 * 記事カテゴリに関連するデータベース用のリポジトリクラス
 * @package App\Repositories
 */
class ArticleCategoryMariadbRepository extends AbstractMariadbRepository implements ArticleCategoryRepositoryInterface
{
    /**
     * ArticleCategoryMysqlRepository constructor.
     */
    public function __construct()
    {
        parent::__construct('article_categories');
    }

    /**
     * 記事カテゴリを連想配列の形式で返す。戻り値は、以下のようになる。
     * [1 => 'ランチ', 2 => 'ディナー', 3 => 'ティータイム']
     */
    public function findAllAsMap(): array
    {
        $statement = $this->connection->prepare('SELECT * FROM article_categories order by sort asc');
        $statement->execute();
        $records = $statement->fetchAll(PDO::FETCH_ASSOC);
        return array_column($records, 'category', 'code');
    }
}