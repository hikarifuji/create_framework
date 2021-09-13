<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Libs\AbstractUserController;
use App\Libs\Core\Exception\PageNotFoundException;
use App\Models\ArticleModel;
use App\Models\ArticleCategoryModel;
use App\Libs\Pager;

/**
 * 記事に関連する画面コントローラー
 * @package App\Controllers
 */
class ArticleController extends AbstractUserController
{
    /**
     * @var ArticleModel 記事モデルクラスのインスタンス
     */
    private ArticleModel $articleModel;

    /**
     * ArticleController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->articleModel = new ArticleModel();
        $this->view->categories = $this->articleModel->findCategories();
        $this->view->perPages = [
            3 => '3件',
            10 => '10件',
            30 => '30件',
        ];
    }

    /**
     * 記事一覧ページに関連するコントロール処理を行う
     */
    public function indexAction(): void
    {
        $this->view->layoutTitle = '記事';
        $conditions = [];
        if ($this->request->hasGet('search')) {
            $perPage = $this->view->perPage = $this->request->byGetAsInt('per_page') ?? 10;
            $conditions['category'] = $this->view->category = $this->request->byGetAsInt('category');
        } else {
            $perPage = $this->view->perPage = 10;
        }
        $page = $this->view->page = $this->request->byGetAsInt('page') ?? 1;
        $this->view->articles = $this->articleModel->find($conditions, $perPage, $page);
        $count = $this->view->count = $this->articleModel->getCount($conditions);
        $this->view->pager = new Pager($count, $page, $perPage);
        echo $this->view->render();
    }

    /**
     * 記事ランキングページに関連するコントロール処理を行う
     */
    public function rankAction(): void
    {
        $this->view->layoutTitle = '記事ランキング';
        $this->view->articles = $this->articleModel->findByRank(10);
        echo $this->view->render();
    }

    /**
     * 記事詳細ページに関連するコントロール処理を行う
     */
    public function detailAction(): void
    {
        $this->view->layoutTitle = '記事';
        $articleId = $this->request->byGetAsInt('id');
        if (is_null($articleId)) {
            throw new PageNotFoundException();
        }
        if ($this->request->hasPost('send-like')) {
            $this->checkCsrfToken();
            $this->articleModel->countUpLike($articleId);
        } elseif ($this->request->hasPost('send-comment')) {
            $this->checkCsrfToken();
            $this->articleModel->createComment($articleId, $this->session->getUserId(), $this->request->byPost('comment'));
        } else {
            $this->articleModel->countUpPv($articleId);
        }
        $this->view->csrfToken = $this->session->generateCsrfToken();
        $this->view->article = $this->articleModel->findOne($articleId);
        $this->view->comments = $this->articleModel->findComments($articleId);
        echo $this->view->render();
    }
}