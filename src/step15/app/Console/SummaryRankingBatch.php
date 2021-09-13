<?php

declare(strict_types=1);

namespace App\Console;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Models\ArticleModel;
use App\Libs\Core\ConsoleApplication;

/**
 * PV数、おいしイイね数に応じて、記事ごとのランクをつけるバッチ処理。
 * 本処理は、1日に1度、cronなどを使って自動実行することを想定します。
 */
class SummaryRankingBatch extends ConsoleApplication
{
    /**
     * @var array コマンドラインオプション
     */
    private array $options;

    /**
     * @var ArticleModel ArticleModelインスタンス
     */
    private ArticleModel $articleModel;

    /**
     * SummaryRankingBatch constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        parent::__construct();
        $this->options = $options;
        $this->articleModel = new ArticleModel();
    }

    /**
     * 本プログラムのメイン処理にあたるメソッド
     */
    protected function job(): void
    {
        $popularArticles = $this->articleModel->findPopularArticles(10);
        if (isset($this->options['dry-run'])) {
            print_r($popularArticles);
            return;
        }
        $popularArticleIds = array_column($popularArticles, 'id');
        $this->articleModel->saveRanks($popularArticleIds);
    }
}

// メインルーチン
$options = getopt('', ['dry-run']);
$batch = new SummaryRankingBatch($options);
$batch->execute();
