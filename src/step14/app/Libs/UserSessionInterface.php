<?php

declare(strict_types=1);

namespace App\Libs;

/**
 * 公開ページ用のセッションインターフェース
 * @package App\Libs
 */
interface UserSessionInterface
{
    /**
     * セッション情報をセットする
     * @param string $name セッションキー
     * @param mixed $value セッション値
     */
    public function set(string $name, $value): void;

    /**
     * セッション情報を取得する
     * @param string $name セッションキー
     * @return mixed|null セッション名に対応するセッション値
     */
    public function get(string $name);

    /**
     * すべてのセッション情報を取得する
     * @return array
     */
    public function getAll();

    /**
     * すべてのセッション情報を、キー名のみ取得する
     * @return array
     */
    public function getAllKeys(): array;

    /**
     * セッションキー指定でセッション値を削除する
     * @param $name セッションキー
     */
    public function remove(string $name): void;

    /**
     * すべてのセッション情報を削除する。
     * サーバー上のセッション情報やセッションクッキーも削除したいときはdestroyメソッドを使ってください。
     */
    public function clear(): void;

    /**
     * すべてのセッション情報を削除し、サーバー上のセッション情報やセッションクッキーも削除する
     */
    public function destroy(): void;

    /**
     * セッションIDを再生成する
     * @param bool $deleteOldSession 古いセッションIDを削除するときは真
     */
    public function regenerate($deleteOldSession = true): void;

    /**
     * ログイン済であるかを判定する
     * @return bool ログイン済なら真
     */
    public function isLogin(): bool;

    /**
     * ログイン情報からユーザIDを取得する
     * @return int|null ユーザID。未ログイン時はnullを返す
     */
    public function getUserId(): ?int;

    /**
     * ログイン情報以外のセッション情報を削除する
     */
    public function clearExceptingLoginKey(): void;

    /**
     * ログイン情報をセットする
     */
    public function setLoginInfo(UserLoginInfo $loginInfo): void;

    /**
     * ランダムなCSRFトークンを生成し、セッションに保存する
     * @return string 生成されたCSRFトークン値
     */
    public function generateCsrfToken(): string;

    /**
     * POSTされたCSRFトークンと、セッションに保存されたCSRFトークンの一致判定をする
     * @param string $receivedToken POSTにより受け取ったトークン値
     * @return bool 2つのトークンが一致していれば真
     */
    public function checkCsrfToken(string $receivedToken): bool;
}