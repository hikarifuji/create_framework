<?php

declare(strict_types=1);

namespace App\Libs\Core;

/**
 * セッションクラス
 * @package App\Libs\Core
 */
abstract class Session
{
    /**
     * Session constructor.
     */
    public function __construct()
    {
        session_start();
    }

    /**
     * セッション情報をセットする
     * @param string $name セッションキー
     * @param mixed $value セッション値
     */
    public function set(string $name, $value): void
    {
        $_SESSION[$name] = $value;
    }

    /**
     * セッション情報を取得する
     * @param string $name セッションキー
     * @return mixed|null セッション名に対応するセッション値
     */
    public function get(string $name)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
        return null;
    }

    /**
     * すべてのセッション情報を取得する
     * @return array
     */
    public function getAll()
    {
        return $_SESSION;
    }

    /**
     * すべてのセッション情報を、キー名のみ取得する
     * @return array
     */
    public function getAllKeys(): array
    {
        return array_keys($_SESSION);
    }

    /**
     * セッションキー指定でセッション値を削除する
     * @param $name セッションキー
     */
    public function remove(string $name): void
    {
        unset($_SESSION[$name]);
    }

    /**
     * すべてのセッション情報を削除する。
     * サーバー上のセッション情報やセッションクッキーも削除したいときはdestroyメソッドを使ってください。
     */
    public function clear(): void
    {
        $_SESSION = [];
    }

    /**
     * セッションが存在するかを判定する
     */
    public function exists(string $name)
    {
        return isset($_SESSION[$name]);
    }

    /**
     * すべてのセッション情報を削除し、サーバー上のセッション情報やセッションクッキーも削除する
     */
    public function destroy(): void
    {
        $this->clear();
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();
    }

    /**
     * セッションIDを再生成する
     * @param bool $deleteOldSession 古いセッションIDを削除するときは真
     */
    public function regenerate($deleteOldSession = true): void
    {
        session_regenerate_id($deleteOldSession);
    }
}
