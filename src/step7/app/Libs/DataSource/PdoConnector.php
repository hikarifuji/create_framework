<?php

declare(strict_types=1);

namespace App\Libs\DataSource;

use PDO;

/**
 * PDOによるデータベース接続クラス
 * @package App\Libs
 */
class PdoConnector
{
    /**
     * @var ?PDO PDOインスタンス
     */
    private static ?PDO $connection = null;

    /**
     * データベース接続済のPDOインスタンスを返す
     */
    public function connect(): PDO
    {
        if (self::$connection === null) {
            self::$connection = new PDO(
                'mysql:host=mariadb; dbname=enjoy_eats; charset=utf8mb4',
                'root',
                'abcde123'
            );
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        return self::$connection;
    }
}
