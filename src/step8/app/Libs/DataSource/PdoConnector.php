<?php

declare(strict_types=1);

namespace App\Libs\DataSource;

use PDO;
use App\Libs\ApplicationConfigs;

/**
 * PDOによるデータベース接続クラス
 * @package App\Libs
 */
class PdoConnector
{
    /**
     * @var PDO PDOインスタンス
     */
    private static ?PDO $connection = null;

    /**
     * データベース接続済のPDOインスタンスを返す
     */
    public function connect(): PDO
    {
        if (self::$connection === null) {
            $dbConfig = ApplicationConfigs::getInstance()->getDatabase();
            self::$connection = new PDO($dbConfig['dsn'], $dbConfig['user'], $dbConfig['password']);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        return self::$connection;
    }
}
