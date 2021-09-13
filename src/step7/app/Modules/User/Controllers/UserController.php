<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Libs\Core\View;
use App\Models\TestModel;

/**
 * ユーザに関連する画面コントローラー
 * @package App\Controllers
 */
class UserController
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
    }

    /**
     * ユーザ一覧画面のコントロール処理を行う
     */
    public function indexAction(): void
    {
        echo 'UserController::indexAction()がコールされました。';
        $model = new TestModel();
        $createdId = $model->create();
        $view = new View(
            __DIR__ . '/../Views/user/index.html',
            __DIR__ . '/../Layouts/layout.html'
        );
        $view->message1 = "testsテーブルにID:{$createdId}のレコードをINSERTしました。";
        echo $view->render();
    }

    /**
     * ユーザ詳細画面のコントロール処理を行う
     */
    public function showAction($params): void
    {
        echo 'UserController::showAction()がコールされました。';
        echo '指定されたユーザIDは：', $params['user-id'];
    }

}