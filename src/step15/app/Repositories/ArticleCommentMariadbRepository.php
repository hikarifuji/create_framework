<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Libs\AbstractMariadbRepository;
use PDO;

/**
 * 記事コメントに関連するデータベース用のリポジトリクラス
 * @package App\Repositories
 */
class ArticleCommentMariadbRepository extends AbstractMariadbRepository implements ArticleCommentRepositoryInterface
{
    /**
     * ArticleCommentRepository constructor.
     */
    public function __construct()
    {
        parent::__construct('article_comments');
    }
}