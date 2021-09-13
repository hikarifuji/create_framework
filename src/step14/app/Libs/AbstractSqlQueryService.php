<?php

declare(strict_types=1);

namespace App\Libs;

use App\Libs\DataSource\PdoConnector;
use PDO;

/**
 * リレーショナルデータベース用の汎用的なリポジトリクラス
 * @package App\Libs\Core
 */
abstract class AbstractSqlQueryService
{
    /**
     * @var PDO データベース接続インスタンス
     */
    protected PDO $connection;

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->connection = (new PdoConnector())->connect();
    }
}