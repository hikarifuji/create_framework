<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Libs\AbstractUserController;
use App\Models\ArticleModel;

/**
 * 記事の投稿に関連する画面コントローラー
 * @package App\Controllers
 */
class PostController extends AbstractUserController
{
    /**
     * 投稿する項目を表す配列。
     * HTMLフォームのname属性と一致する。
     */
    private array $items = [
        'category',
        'title',
        'contents',
    ];

    /**
     * 記事モデルクラスのインスタンス
     */
    private ArticleModel $articleModel;

    /**
     * PostController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        if ($this->session->isLogin() !== true) {
            $this->forwardTo(302, '/auth/login');
            return;
        }
        $this->articleModel = new ArticleModel();
        $this->view->categories = $this->articleModel->findCategories();
    }

    /**
     * 記事の入力ページに関連するコントロール処理を行う
     */
    public function indexAction(): void
    {
        $this->view->layoutTitle = '記事を投稿する';
        if ($this->request->hasPost('send')) {
            $errors = [];
            $errors = array_merge($errors, $this->articleModel->validate($this->request->getAllPost()));
            $uploadConfigs = $this->articleModel->getUploadConfigs();
            foreach ($uploadConfigs as $uploadConfig) {
                $errors = array_merge($errors, $this->articleModel->validatePhoto($uploadConfig));
            }
            if (count($errors) > 0) {
                $this->view->errors = $errors;
                $this->view->input = $this->keepInput();
            } else {
                $this->uploadPhotos($uploadConfigs);
                $this->setInputSession();
                $this->forwardTo(303, '/post/confirm');
            }
        }
        echo $this->view->render();
    }

    /**
     * 記事の投稿確認ページに関連するコントロール処理を行う
     */
    public function confirmAction(): void
    {
        $this->view->layoutTitle = '記事を投稿する';
        $this->view->csrfToken = $this->session->generateCsrfToken();
        foreach ($this->session->getAllKeys() as $key) {
            $this->view->{$key} = $this->session->get($key);
        }
        echo $this->view->render();
    }

    /**
     * 記事の投稿完了ページに関連するコントロール処理を行う
     */
    public function completeAction(): void
    {
        $this->view->layoutTitle = '記事を投稿する';
        $this->checkCsrfToken();
        $this->articleModel->create($this->session->getAll(), $this->session->getUserId());
        $this->session->clearExceptingLoginKey();
        echo $this->view->render();
    }

    /**
     * 記事の入力ページでのバリデーションエラー時に、入力内容を保持する
     */
    private function keepInput(): void
    {
        foreach ($this->items as $item) {
            $this->view->{$item} = $this->request->byPost($item);
        }
    }

    /**
     * 記事の入力ページでのアップロード処理を行う。
     * この時点では投稿が未完了であるため、仮ディレクトリにアップロードする。
     * @param array $uploadConfigs
     */
    private function uploadPhotos(array $uploadConfigs): void
    {
        foreach ($uploadConfigs as $uploadConfig) {
            $uploadedPath = $this->articleModel->uploadPhoto($uploadConfig);
            $this->session->set($uploadConfig->getName(), $uploadedPath);
            if ($uploadedPath) {
                $this->articleModel->resizeImage($uploadedPath);
            }
        }
    }

    /**
     * 記事の入力ページでの入力値をセッションに保存する
     */
    private function setInputSession(): void
    {
        foreach ($this->items as $item) {
            $this->session->set($item, $this->request->byPost($item));
        }
    }
}