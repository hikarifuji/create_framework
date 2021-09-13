<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Libs\AbstractUserController;
use App\Models\AuthModel;
use App\Libs\UserLoginInfo;

/**
 * ログインに関連する画面コントローラー
 * @package App\Controllers
 */
class AuthController extends AbstractUserController
{
    /**
     * @var AuthModel 認証モデルクラスのインスタンス
     */
    private AuthModel $authModel;

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->authModel = new AuthModel();
    }

    /**
     * ログインページに関連するコントロール処理を行う
     */
    public function loginAction(): void
    {
        $this->view->layoutTitle = 'ログイン';
        if ($this->request->hasPost('send')) {
            $this->checkCsrfToken();
            $loginInfo = $this->authModel->login($this->request->byPost('mail'), $this->request->byPost('password'));
            if (!($loginInfo instanceof UserLoginInfo)) {
                $this->view->errors = ['ログインに失敗しました。'];
            } else {
                $this->session->setLoginInfo($loginInfo);
                $this->session->regenerate(true);
                $this->forwardTo(303, '/auth/login-complete');
                return;
            }
        }
        $this->view->csrfToken = $this->session->generateCsrfToken();
        echo $this->view->render();
    }

    /**
     * ログイン成功画面に関連するコントロール処理を行う
     */
    public function loginCompleteAction(): void
    {
        $this->view->layoutTitle = 'ログイン';
        echo $this->view->render();
    }

    /**
     * ログアウト画面に関連するコントロール処理を行う
     */
    public function logoutAction(): void
    {
        $this->view->layoutTitle = 'ログアウト';
        $this->session->destroy();
        $this->view->isLogin = false;
        echo $this->view->render();
    }
}