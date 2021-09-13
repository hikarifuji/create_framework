<?php

declare(strict_types=1);

namespace App\Libs;

/**
 * 公開ページ用のセッションクラス
 * @package App\Libs
 */
class UserSession extends Core\Session implements UserSessionInterface
{
    /**
     * ログイン情報を表すセッションキー
     */
    private const LOGIN_KEY = 'auth';

    /**
     * CSRFトークン情報を表すセッションキー
     */
    private const CSRF_KEY = 'csrf-token';

    /**
     * UserSession constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * ログイン済であるかを判定する
     * @return bool ログイン済なら真
     */
    public function isLogin(): bool
    {
        return
            $this->exists(self::LOGIN_KEY) &&
            $this->get(self::LOGIN_KEY)->getId();
    }

    /**
     * ログイン情報からユーザIDを取得する
     * @return int|null ユーザID。未ログイン時はnullを返す
     */
    public function getUserId(): ?int
    {
        return $this->exists(self::LOGIN_KEY) ?
            intval($this->get(self::LOGIN_KEY)->getId()) : null;
    }

    /**
     * ログイン情報以外のセッション情報を削除する
     */
    public function clearExceptingLoginKey(): void
    {
        $loginInfo = $this->get(self::LOGIN_KEY);
        $this->clear();
        $this->set(self::LOGIN_KEY, $loginInfo);
    }

    /**
     * ログイン情報をセットする
     */
    public function setLoginInfo(UserLoginInfo $loginInfo): void
    {
        $this->set(self::LOGIN_KEY, $loginInfo);
    }

    /**
     * ランダムなCSRFトークンを生成し、セッションに保存する
     * @return string 生成されたCSRFトークン値
     */
    public function generateCsrfToken(): string
    {
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $this->set(self::CSRF_KEY, $token);
        return $token;
    }

    /**
     * POSTされたCSRFトークンと、セッションに保存されたCSRFトークンの一致判定をする
     * @param string $receivedToken POSTにより受け取ったトークン値
     * @return bool 2つのトークンが一致していれば真
     */
    public function checkCsrfToken(string $receivedToken): bool
    {
        $savedToken = $this->get(self::CSRF_KEY);
        if (!isset($savedToken) || !isset($receivedToken) || $savedToken !== $receivedToken) {
            return false;
        }
        return true;
    }
}