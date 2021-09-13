<?php

declare(strict_types=1);

namespace App\Models;

use App\Libs\Core\Container;
use App\Libs\Validator;
use App\Libs\Uploader\FileUploader;
use App\Libs\Uploader\FileUploadConfig;
use App\Libs\ApplicationConfigs;
use App\Libs\Core\AbstractModel;
use App\QueryServices\ArticleQueryServiceInterface;
use App\Repositories\ArticleCategoryRepositoryInterface;
use App\Repositories\ArticleCommentRepositoryInterface;
use App\Repositories\ArticleRepositoryInterface;

/**
 * 記事に関連するモデルクラス
 * @package App\Models
 */
class ArticleModel extends AbstractModel
{
    /**
     * @var ArticleQueryServiceInterface 記事に関連するクエリサービスのインスタンス
     */
    private ArticleQueryServiceInterface $articleQueryService;

    /**
     * @var ArticleRepositoryInterface 記事リポジトリクラスのインスタンス
     */
    private ArticleRepositoryInterface $articleRepository;

    /**
     * @var ArticleCategoryRepositoryInterface 記事カテゴリリポジトリクラスのインスタンス
     */
    private ArticleCategoryRepositoryInterface $articleCategoryRepository;

    /**
     * @var ArticleCommentRepositoryInterface 記事コメントリポジトリクラスのインスタンス
     */
    private ArticleCommentRepositoryInterface $articleCommentRepository;

    /**
     * ArticleModel constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->articleQueryService = Container::getInstance()->get('ArticleQueryService');
        $this->articleRepository = Container::getInstance()->get('ArticleRepository');
        $this->articleCategoryRepository = Container::getInstance()->get('ArticleCategoryRepository');
        $this->articleCommentRepository = Container::getInstance()->get('ArticleCommentRepository');
    }

    /**
     * 記事カテゴリを連想配列の形式で返す。戻り値は、以下のようになる。
     * [1 => 'ランチ', 2 => 'ディナー', 3 => 'ティータイム']
     */
    public function findCategories()
    {
        return $this->articleCategoryRepository->findAllAsMap();
    }

    /**
     * 条件に応じた複数の記事を取得する
     * @param array $conditions 絞り込み条件を表す連想配列
     * @param int $perPage 1ページあたりの表示件数
     * @param int $page 現在のページ番号
     * @return array 検索結果のレコード配列
     */
    public function find(array $conditions, int $perPage, int $page): array
    {
        /*
        ページング処理を行うときは、以下のようなSQLを組み立てる
        -- 1ページ目(1レコード目～ 3レコード目)
        SELECT * FROM articles ORDER BY changed DESC LIMIT 3 OFFSET 0;
        -- 2ページ目(4レコード目～ 6レコード目)
        SELECT * FROM articles ORDER BY changed DESC LIMIT 3 OFFSET 3;
        -- 3ページ目(7レコード目～ 9レコード目)
        SELECT * FROM articles ORDER BY changed DESC LIMIT 3 OFFSET 6;
        */
        $page = $page ?? 1;
        $offset = $perPage * ($page - 1);
        return $this->articleQueryService->findArticles($conditions, 'changed DESC', $perPage, $offset);
    }

    /**
     * 条件に応じた記事の件数を取得する
     * @param array $conditions 絞り込み条件を表す連想配列
     * @return int 検索結果の件数
     */
    public function getCount(array $conditions): int
    {
        return $this->articleRepository->getCount($conditions);
    }

    /**
     * 記事ID指定で、1件のみ、レコードを取得する
     * @param int $id 記事ID
     * @return array 記事レコード
     */
    public function findOne(int $id): array
    {
        return $this->articleRepository->findOneById($id);
    }

    /**
     * 指定された記事IDに対する、コメントの配列(article_commentsレコードの配列)を返す
     * @param int $articleId 記事ID
     * @return array 記事に対するコメントの配列
     */
    public function findComments(int $articleId): array
    {
        return $this->articleQueryService->findComments($articleId, 'changed DESC');
    }

    /**
     * 記事に対する、おいしイイね数を1カウントアップする
     * @param int $articleId 記事ID
     */
    public function countUpLike(int $articleId): void
    {
        $this->articleRepository->countUpLike($articleId);
    }

    /**
     * 記事に対する、PV数を1カウントアップする
     * @param int $articleId 記事ID
     */
    public function countUpPv(int $articleId): void
    {
        $this->articleRepository->countUpPv($articleId);
    }

    /**
     * 記事に対するコメントを追加する
     * @param int $articleId 記事ID
     * @param int $userId ユーザID
     * @param string|null $comment コメント本文
     */
    public function createComment(int $articleId, int $userId, ?string $comment): void
    {
        if (is_null($comment) || $comment === '') {
            return;
        }
        $datas = [
            'user_id' => $userId,
            'article_id' => $articleId,
            'comment' => $comment
        ];
        $this->articleCommentRepository->create($datas);
    }

    /**
     * 記事IDと、その記事の画像ファイル名をもとに、画像ファイルの絶対パスを組み立てる
     * @param int $articleId 記事ID
     * @param string $photoName その記事の画像ファイル名
     * @return string|null 画像ファイルの絶対パス。存在しない画像であった場合はnull。
     */
    public function buildPhotoPath(int $articleId, string $photoName): ?string
    {
        if (intval($articleId) <= 0 || trim($photoName) === '') {
            return null;
        }
        $photoPath =
            ApplicationConfigs::getInstance()->getPaths()['upload'] .
            '/articles/' .
            $articleId .
            '/' .
            $photoName;
        return file_exists($photoPath) ? $photoPath : null;
    }

    /**
     * 記事を投稿時のバリデーションを行う
     * @param array $datas ユーザの入力データを表す連想配列
     * @return array バリデーションエラー時のエラーメッセージ配列
     */
    public function validate(array $datas): array
    {
        $errors = [];
        $mustItems = [
            'category' => 'カテゴリ',
            'title' => 'タイトル',
            'contents' => '本文',
        ];
        foreach ($mustItems as $itemName => $itemLabel) {
            if (Validator::hasValue($datas[$itemName]) !== true) {
                $errors[] = "{$itemLabel}は必須です。";
            }
        }
        return $errors;
    }

    /**
     * 記事を投稿時の、画像のバリデーションを行う
     * @param FileUploadConfig $uploadConfig アップロード設定情報。許可する拡張子やサイズ上限を指定すること
     * @return array バリデーションエラー時のエラーメッセージ配列
     */
    public function validatePhoto(FileUploadConfig $uploadConfig): array
    {
        $errors = [];
        $uploader = new FileUploader($uploadConfig);
        if ($uploader->validate() !== true) {
            $errors[] = $uploader->getErrorMessage();
        }
        return $errors;
    }

    /**
     * 記事の入力ページでのアップロード処理を行う。
     * この時点では投稿が未完了であるため、仮ディレクトリにアップロードする。
     * @param array $uploadConfigs
     */
    public function uploadPhoto(FileUploadConfig $uploadConfig)
    {
        $uploader = new FileUploader($uploadConfig);
        return $uploader->upload();
    }

    /**
     * 記事の人気ランキング順位をうけとり、データベースに書き込む
     * @param array $popularArticleIds 人気のある記事のIDを保持する配列。0番目に1位のID、1番目に2位のID...のように指定
     */
    public function saveRanks(array $popularArticleIds): void
    {
        $this->articleRepository->updateAll(['rank' => null]);
        for ($rank = 1; $rank <= count($popularArticleIds); $rank++) {
            $this->articleRepository->updateOne($popularArticleIds[$rank - 1], ['rank' => $rank]);
        }
    }

    /**
     * 記事を人気ランキング順に取得する。
     * このメソッドではarticles#rankカラムは参照せずに、likesカラムとpvカラムのみを見て記事の人気を判断する
     * @param int $limit 取得する最大件数。たとえば、30位まで取得したいときは30を指定する
     * @return array 人気ランキング順の記事の配列。0番目に1位の記事、1番目に2位の記事...のようになる。
     */
    public function findPopularArticles(int $limit): array
    {
        return $this->articleQueryService->findPopularArticles($limit);
    }

    /**
     * 記事を人気ランキング順に取得する。
     * このメソッドではarticles#rankカラムのみを参照して記事の人気を判断する
     * @param int $limit 取得する最大件数。たとえば、30位まで取得したいときは30を指定する
     * @return array 人気ランキング順の記事の配列。0番目に1位の記事、1番目に2位の記事...のようになる。
     */
    public function findByRank(int $limit): array
    {
        return $this->articleQueryService->findByRank($limit);
    }

    /**
     * 記事画像のアップロード設定情報(許可する拡張子や、アップロード後のファイル名など)を取得する。
     * @return array アップロード設定情報。FileUploadConfigインスタンスの配列
     */
    public function getUploadConfigs(): array
    {
        $uploadConfigs = [];
        for ($photoNumber = 1; $photoNumber <= 3; $photoNumber++) {
            $uploadConfig = new FileUploadConfig();
            $uploadConfig->setName('photo' . $photoNumber);
            $uploadConfig->setLabel('写真' . $photoNumber);
            $uploadConfig->setExtensions(['jpg', 'jpeg', 'png', 'gif']);
            $uploadConfig->setMimeTypes(['image/jpeg', 'image/png', 'image/gif']);
            $uploadConfig->setMaxBytes(1024 * 1024 * 3); // 3Mb
            $uploadConfig->setNewName(date('YmdHis-') . bin2hex(random_bytes(16)));
            $uploadConfig->setDestination(ApplicationConfigs::getInstance()->getPaths()['upload'] . '/temporary');
            $uploadConfigs[] = $uploadConfig;
        }
        return $uploadConfigs;
    }

    /**
     * 記事画像をリサイズする
     * @param string $filePath リサイズ対象画像のファイルパス
     */
    public function resizeImage(string $filePath): void
    {
        if (!file_exists($filePath)) {
            return;
        }
        $image = new \App\Libs\ImageManipulator($filePath);
        $image->resize(height: 200);
        $image->save($filePath);
    }

    /**
     * 投稿された記事をデータベースに書き込み、記事画像をアップロードディレクトリに保存する
     * @param array $datas ユーザの入力データ
     * @param int $userId ユーザID
     */
    public function create(array $datas, int $userId): void
    {
        $this->articleRepository->beginTransaction();
        $newId = $this->articleRepository->create([
            'user_id' => $userId,
            'category' => $datas['category'],
            'title' => $datas['title'],
            'contents' => $datas['contents'],
        ]);
        // 記事画像を保存する
        $photoFiles = [];
        $photoDir =  ApplicationConfigs::getInstance()->getPaths()['upload'] . '/articles/' . $newId;
        if (!file_exists($photoDir)) {
            mkdir($photoDir);
        }
        for ($photoNumber = 1; $photoNumber <= 3; $photoNumber++) {
            if ($datas['photo' . $photoNumber] && file_exists($datas['photo' . $photoNumber])) {
                $renameTo = $photoDir . '/' . basename($datas['photo' . $photoNumber]);
                rename($datas['photo' . $photoNumber], $renameTo);
                $photoFiles[$photoNumber] = basename($datas['photo' . $photoNumber]);
            } else {
                $photoFiles[$photoNumber] = null;
            }
        }
        $datas = [];
        for ($photoNumber = 1; $photoNumber <= 3; $photoNumber++) {
            $datas["photo{$photoNumber}"] = $photoFiles[$photoNumber];
        }
        $this->articleRepository->updateOne($newId, $datas);
        $this->articleRepository->commit();
    }
}