<?php

declare(strict_types=1);

namespace App\Libs;

/**
 * Mariadbデータベース用の汎用的なリポジトリクラス
 * @package App\Libs\Core
 */
abstract class AbstractMariadbQueryService extends AbstractSqlQueryService
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
    }
}