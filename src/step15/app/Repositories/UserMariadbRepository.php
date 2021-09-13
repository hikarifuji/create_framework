<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Libs\AbstractMariadbRepository;
use PDO;

/**
 * ユーザに関連するデータベース用のリポジトリクラス
 * @package App\Repositories
 */
class UserMariadbRepository extends AbstractMariadbRepository implements UserRepositoryInterface
{
    /**
     * UserMysqlRepository constructor.
     */
    public function __construct()
    {
        parent::__construct('users');
    }

    /**
     * レコードを1件取得する
     */
    public function findOne(array $conditions)
    {
        $sql = 'SELECT * FROM users WHERE 1 = 1';
        if (isset($conditions['id'])) {
            $sql .= ' AND id = :id ';
        }
        if (isset($conditions['mail'])) {
            $sql .= ' AND mail = :mail ';
        }
        $statement = $this->connection->prepare($sql);
        if (isset($conditions['id'])) {
            $statement->bindValue(':id', $conditions['id'], PDO::PARAM_INT);
        }
        if (isset($conditions['mail'])) {
            $statement->bindValue(':mail', $conditions['mail'], PDO::PARAM_STR);
        }
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}