<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Libs\AbstractUserController;

/**
 * ユーザに関連する画面コントローラー
 * @package App\Controllers
 */
class UserController extends AbstractUserController
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * ユーザ一覧画面のコントロール処理を行う
     */
    public function indexAction(): void
    {
        // 「送信する」ボタンが押下されたときに、このifブロックに入ります。
        // ブロック内のプログラム処理で、実験用の出力を行います。
        if ($this->request->hasPost('send')) {
            // GETパラメータ、POSTパラメータを出力します。
            echo <<< EOM
<pre>
氏名：{$this->request->byPost('simei')}
住所：{$this->request->byPost('address')}
</pre>
EOM;
        }
        echo $this->view->render();
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