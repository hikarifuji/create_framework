<?php

declare(strict_types=1);

namespace App\Libs;

use App\Libs\DataSource\PdoConnector;
use PDO;

/**
 * リレーショナルデータベース用の汎用的なリポジトリクラス
 * @package App\Libs\Core
 */
abstract class AbstractSqlRepository
{
    /**
     * @var PDO データベース接続インスタンス
     */
    protected PDO $connection;

    /**
     * @var string 操作対象のテーブル名
     */
    protected string $tableName;

    /**
     * コンストラクタ
     */
    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
        $this->connection = (new PdoConnector())->connect();
    }

    /**
     * トランザクションを開始する
     */
    public function beginTransaction(): void
    {
        if (!$this->connection->inTransaction()) {
            $this->connection->beginTransaction();
        }
    }

    /**
     * トランザクションをコミットする
     */
    public function commit(): void
    {
        if ($this->connection->inTransaction()) {
            $this->connection->commit();
        }
    }

    /**
     * トランザクションをロールバックする
     */
    public function rollback(): void
    {
        if ($this->connection->inTransaction()) {
            $this->connection->rollback();
        }
    }

    /**
     * レコードを1件、更新する
     * @param int $id idカラム値
     * @param array $datas カラム名とカラム値からなる連想配列
     */
    public function updateOne(int $id, array $datas): void
    {
        $this->updateRecord($this->tableName, $id, $datas);
    }

    /**
     * レコードを全件更新する
     * @param array $datas カラム名とカラム値からなる連想配列
     */
    public function updateAll(array $datas): void
    {
        $this->updateRecord($this->tableName, null, $datas);
    }

    /**
     * レコードを挿入する
     * @param array $datas カラム名とカラム値からなる連想配列
     * @return string 挿入されたレコードのid値
     */
    public function create(array $datas): int
    {
        return $this->createRecord($this->tableName, $datas);
    }

    /**
     * レコードを生成(INSERT)する
     * @param string $table テーブル名
     * @param array $datas カラムと値からなる連想配列
     */
    protected function createRecord(string $table, array $datas): int
    {
        $sql = "INSERT INTO {$table}(";
        $sql .= implode(',', array_keys($datas));
        $sql .= ") values(";
        $placeHolders = array_keys($datas);
        array_walk($placeHolders, function(&$placeHolder) {
            $placeHolder = ":{$placeHolder}";
        });
        $sql .= implode(',', $placeHolders);
        $sql .= ')';
        $statement = $this->connection->prepare($sql);
        foreach ($datas as $column => $value) {
            $statement->bindValue(":{$column}", $value, $this->getPdoParamType($value));
        }
        $statement->execute();
        return $this->lastInsertId();
    }

    /**
     * 直近にinsertしたIDカラム値(またはシーケンス値)を取得する
     * @param string|null $sequenceName シーケンス名
     * @return int|null シーケンス値
     */
    abstract protected function lastInsertId(?string $sequenceName = null): ?int;

    /**
     * レコードを更新(UPDATE)する
     * @param string $table テーブル名
     * @param int|null $id idカラム値。nullの場合は全件アップデート
     * @param array $datas カラム名と値の連想配列
     */
    protected function updateRecord(string $table, ?int $id, array $datas): void
    {
        $sql = "UPDATE {$table} SET ";
        $placeHolders = [];
        foreach ($datas as $name => $value) {
            $placeHolders[] = "{$name} = :{$name}";
        }
        $sql .= implode(',', $placeHolders);
        if ($id) {
            $sql .= " WHERE id = :id";
        }
        $statement = $this->connection->prepare($sql);
        if ($id) {
            $statement->bindValue(":id", $id, PDO::PARAM_INT);
        }
        foreach ($datas as $column => $value) {
            $statement->bindValue(":{$column}", $value, $this->getPdoParamType($value));
        }
        $statement->execute();
    }

    /**
     * 値に応じたPDOのデータ型定数を返す
     */
    private function getPdoParamType($value)
    {
        if (is_int($value)) {
            return PDO::PARAM_INT;
        } elseif (is_string($value)) {
            return PDO::PARAM_STR;
        } elseif (is_null($value)) {
            return PDO::PARAM_NULL;
        }
        return null;
    }
}