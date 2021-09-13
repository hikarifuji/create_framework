<?php

declare(strict_types=1);

namespace App\Models;

use App\Libs\Core\AbstractModel;
use App\Libs\Core\Container;
use App\Libs\Mailer\MailerConfig;
use App\Libs\Validator;
use App\Libs\Password;
use App\Libs\ApplicationConfigs;
use App\Repositories\UserRepositoryInterface;

/**
 * ユーザに関連するモデルクラス
 * @package App\Models
 */
class UserModel extends AbstractModel
{
    /**
     * @var UserRepositoryInterface ユーザリポジトリクラスのインスタンス
     */
    private UserRepositoryInterface $userRepository;

    /**
     * UserModel constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->userRepository = Container::getInstance()->get('UserRepository');
    }

    /**
     * ユーザ登録の初回画面で、メールアドレスのバリデーションを行う
     * @param array $datas ユーザの入力値
     * @return array バリデーションエラー時のエラーメッセージ配列
     */
    public function validateOnStart(array $datas): array
    {
        $errors = [];
        $mustItems = [
            'mail' => 'メールアドレス',
            'mail_confirm' => 'メールアドレス(確認)',
        ];
        foreach ($mustItems as $itemName => $itemLabel) {
            if (Validator::hasValue($datas[$itemName]) !== true) {
                $errors[] = "{$itemLabel}は必須です。";
            }
        }
        if (count($errors) > 0) {
            return $errors;
        }
        if ($datas['mail'] !== $datas['mail_confirm']) {
            $errors[] = 'メールアドレス(確認)が一致しません。';
        }
        if (!filter_var($datas['mail'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "メールアドレスが不正な形式です。";
        }
        if ($datas['mail'] && $this->isMailExists($datas['mail'])) {
            $errors[] = "すでに本登録済のメールアドレスです。";
        }
        return $errors;
    }

    /**
     * すでに登録済のメールアドレスであるかを判定する
     * @param string $mail メールアドレス
     * @return bool 既に登録済のメールアドレスならtrue
     */
    private function isMailExists(string $mail): bool
    {
        $user = $this->userRepository->findOne(['mail' => $mail]);
        if (!$user) {
            return false;
        }
        return intval($user['id']) > 0;
    }

    /**
     * ユーザ登録時に使う、ランダムな認証コードを生成する
     * @param array $datas ユーザ入力値
     * @return string ランダムな認証コード
     */
    public function sendAuthCode(array $datas): string
    {
        $authCode = strval(random_int(min: 100000, max: 999999));
        $mailer = Container::getInstance()->get('mailer');
        $mailConfig = new MailerConfig();
        $mailConfig->setFromAddress(ApplicationConfigs::getInstance()->getMail()['from']);
        $mailConfig->setFromName(ApplicationConfigs::getInstance()->getMail()['sender']);
        $mailConfig->addTo($datas['mail']);
        $mailConfig->setSubject('ユーザ登録用確認コードのお知らせ');
        $body = <<< TEXT
以下の確認コードを入力してください。

{$authCode}

TEXT;

        $mailConfig->setBody($body);
        $mailer->send($mailConfig);
        return $authCode;
    }

    /**
     * ユーザが入力した認証用の確認コードと、セッションに保存された確認コードが一致するかを判定する
     * @param string $inputToken ユーザが入力した確認コード
     * @param string $savedToken セッションに保存された確認コード
     * @return bool 2つの確認コードが一致していれば真
     */
    public function isValidToken(string $inputToken, string $savedToken): bool
    {
        if (trim($inputToken) === '' || trim($savedToken) === '') {
            return false;
        }
        return intval($inputToken) === intval($savedToken);
    }

    /**
     * 新規登録したユーザ情報をデータベースに書き込む
     * @param array $datas ユーザの入力データ
     */
    public function create(array $datas): void
    {
        $values = [
            'name' => $datas['name'],
            'mail' => $datas['mail'],
            'password' => Password::generate($datas['password'])
        ];
        $this->userRepository->create($values);
    }

    /**
     * ユーザ新規登録時のバリデーションを行う
     * @param array $datas ユーザ入力データ
     * @return array バリデーションエラー時のエラーメッセージ配列
     */
    public function validateOnCreate(array $datas): array
    {
        $errors = [];
        $mustItems = [
            'name' => 'ハンドルネーム',
            'password' => 'パスワード',
            'password_confirm' => 'パスワード(確認)',
        ];
        foreach ($mustItems as $itemName => $itemLabel) {
            if (Validator::hasValue($datas[$itemName]) !== true) {
                $errors[] = "{$itemLabel}は必須です。";
            }
        }
        if (count($errors) > 0) {
            return $errors;
        }

        // 本来なら、ここで、パスワードの字種や長さもチェックした方がよい。

        if ($datas['password'] !== $datas['password_confirm']) {
            $errors[] = "パスワード(確認)が一致しません。";
        }

        if ($datas['mail'] && $this->isMailExists($datas['mail'])) {
            $errors[] = "すでに本登録済のメールアドレスです。";
        }
        return $errors;
    }
}