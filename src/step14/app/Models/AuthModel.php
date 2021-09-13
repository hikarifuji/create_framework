<?php

declare(strict_types=1);

namespace App\Models;

use App\Libs\Core\AbstractModel;
use App\Libs\Password;
use App\Libs\UserLoginInfo;
use App\Libs\Core\Container;
use App\Repositories\UserRepositoryInterface;

/**
 * ユーザ認証に関するモデルクラス
 * @package App\Models
 */
class AuthModel extends AbstractModel
{
    /**
     * @var UserRepositoryInterface ユーザリポジトリクラスのインスタンス
     */
    private UserRepositoryInterface $userRepository;

    /**
     * AuthModel constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->userRepository = Container::getInstance()->get('UserRepository');
    }

    /**
     * ログイン認証を行う
     * @param string|null $mail メールアドレス(ログインIDとして使う)
     * @param string|null $password 平文パスワード
     * @return UserLoginInfo|null ログイン成功時はLoginInfoインスタンスを、失敗時はnullを返す
     */
    public function login(?string $mail, ?string $password): ?UserLoginInfo
    {
        if (is_null($mail) || is_null($password)) {
            return null;
        }
        $user = $this->userRepository->findOne(['mail' => $mail]);
        if (!$user || intval($user['id']) <= 0 || trim($user['password']) === '') {
            return null;
        }
        $verified = Password::verify($password, $user['password']);
        if ($verified !== true) {
            return null;
        }
        $loginInfo = new UserLoginInfo();
        $loginInfo->setId($user['id']);
        $loginInfo->setMail($user['mail']);
        $loginInfo->setName($user['name']);
        return $loginInfo;
    }
}