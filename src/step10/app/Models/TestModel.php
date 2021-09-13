<?php

declare(strict_types=1);

namespace App\Models;

use App\Libs\Core\AbstractModel;
use App\Libs\Core\Container;
use App\Repositories\TestRepositoryInterface;

/**
 * テスト用のモデルクラス
 * @package App\Models
 */
class TestModel extends AbstractModel
{
    /**
     * TestRepositoryInterfaceを実装するインスタンス
     */
    private TestRepositoryInterface $testRepository;

    /**
     * TestModel constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->testRepository = Container::getInstance()->get('TestRepository');
    }

    /**
     * PDOの動作確認のためのデータ生成メソッド
     */
    public function create()
    {
        $this->logger->debug('testsテーブルにレコードをINSERTしました。');
        return $this->testRepository->create(['value1' => 'step10']);
    }
}