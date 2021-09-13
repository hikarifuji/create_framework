<?php

declare(strict_types=1);

namespace App\Libs;

/**
 * 主にパスワードのハッシュ化のためのクラス
 * @package App\Libs
 */
class Password
{
    /**
     * ストレッチングのコスト値
     */
    const COST_DEFAULT = 13;

    /**
     * ハッシュを生成する
     * @param string $password 対象となる平文パスワード
     * @param int|null $cost ストレッチングのコスト値
     * @return string ハッシュ化されたパスワード文字列
     */
    public static function generate(string $password, int $cost = null): string
    {
        $cost = $cost ?? self::COST_DEFAULT;
        return password_hash($password, PASSWORD_DEFAULT, ['cost' => $cost]);
    }

    /**
     * 平文パスワードとハッシュ化されたパスワードが同じであるかを判定する
     * @param string $plainPassword 平文パスワード
     * @param string $hashedPassword ハッシュ化されたパスワード
     * @return bool 平文とハッシュが同じであればtrue、同じでなければfalse
     */
    public static function verify(string $plainPassword, string $hashedPassword): bool
    {
        return password_verify($plainPassword, $hashedPassword);
    }
}