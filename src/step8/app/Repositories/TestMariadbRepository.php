<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Libs\AbstractMariadbRepository;

/**
 * テスト用のリポジトリクラス
 * @package App\Repositories
 */
class TestMariadbRepository extends AbstractMariadbRepository implements TestRepositoryInterface
{
    /**
     * TestMariadbRepository constructor.
     */
    public function __construct()
    {
        parent::__construct('tests');
    }
}