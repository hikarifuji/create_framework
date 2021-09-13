<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Libs\Core\Container;
use App\Libs\Core\View;
use App\Libs\ApplicationConfigs;
use App\Libs\Core\UploadedFile;
use App\Libs\Uploader\FileUploader;
use App\Libs\Uploader\FileUploadConfig;

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
        $request = Container::getInstance()->get('request');

        // 「送信する」ボタンが押下されたときに、このifブロックに入ります。
        // ブロック内のプログラム処理で、実験用の出力を行います。
        if ($request->hasPost('send')) {
            echo '<pre>';

            // GETパラメータ、POSTパラメータを出力します。
            echo <<< EOM
氏名：{$request->byPost('simei')}
住所：{$request->byPost('address')}
param1：{$request->byGet('param1')}
param2：{$request->byGet('param2')}


EOM;
            // アップロードファイルの情報を出力します。
            $photo = new UploadedFile('photo');
            $pdf = new UploadedFile('pdf');
            var_dump($photo);
            var_dump($pdf);

            // 画像のバリデーションをし、問題がなければ、アップロードディレクトリに保存します。
            $uploadConfig = new FileUploadConfig();
            $uploadConfig->setName('photo');
            $uploadConfig->setLabel('アップロード画像');
            $uploadConfig->setExtensions(['jpg', 'jpeg', 'png', 'gif']);
            $uploadConfig->setMimeTypes(['image/jpeg', 'image/png', 'image/gif']);
            $uploadConfig->setMaxBytes(1024 * 1024 * 1); // 1Mb
            $uploadConfig->setNewName('photo' . date('YmdHis'));
            $uploadConfig->setDestination(ApplicationConfigs::getInstance()->getPaths()['upload'] . '/temporary');
            $uploader = new FileUploader($uploadConfig);
            if ($uploader->validate() !== true) {
                var_dump('アップロードエラー：' . $uploader->getErrorMessage());
            } else {
                $uploader->upload($uploadConfig);
            }

            // PDFのバリデーションをし、問題がなければ、アップロードディレクトリに保存します。
            $uploadConfig = new FileUploadConfig();
            $uploadConfig->setName('pdf');
            $uploadConfig->setLabel('アップロードPDF');
            $uploadConfig->setExtensions(['pdf']);
            $uploadConfig->setMimeTypes(['application/pdf']);
            $uploadConfig->setMaxBytes(1024 * 1024 * 1); // 1Mb
            $uploadConfig->setNewName('pdf' . date('YmdHis'));
            $uploadConfig->setDestination(ApplicationConfigs::getInstance()->getPaths()['upload'] . '/temporary');
            $uploader = new FileUploader($uploadConfig);
            if ($uploader->validate() !== true) {
                var_dump('アップロードエラー：' . $uploader->getErrorMessage());
            } else {
                $uploader->upload($uploadConfig);
            }

            // セッションに値を入れ、直後に、その値を取り出して出力します。
            $session = Container::getInstance()->get('user.session');
            $session->set('session-param1', 'session-value1');
            $session->set('session-param2', 'session-value2');
            var_dump($session->get('session-param1'));
            var_dump($session->get('session-param2'));

            echo '</pre>';
        }

        $view = new View(
            __DIR__ . '/../Views/user/index.html',
            __DIR__ . '/../Layouts/layout.html'
        );
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