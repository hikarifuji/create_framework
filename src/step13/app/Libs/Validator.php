<?php

declare(strict_types=1);

namespace App\Libs;

/**
 * 汎用的なバリデータークラス。
 * 画面仕様に依存するバリデーション処理は、各モデルクラスに実装すること。
 * @package App\Libs
 */
class Validator
{
    /**
     * Validator constructor.
     */
    public function __construct()
    {
    }

    /**
     * 文字列がRFC 822に沿ったEメールアドレス形式であるかを判定する。
     * 一部の古いキャリアメールの規格では、RFC 822に沿っていないこともあるので注意。
     * @param string|null $value チェック対象の文字列
     * @return bool RFC 822に沿っていれば真
     */
    public static function isEmail(?string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== true ? false : true;
    }

    /**
     * 文字列がURL形式であるかを判定する
     * @param string|null $value チェック対象の文字列
     * @return bool URL形式であれば真
     */
    public static function isUrl(?string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_URL) !== true ? false : true;
    }

    /**
     * 文字列に入力があることを判定する
     * @param string|null $value チェック対象の文字列
     * @param bool $isAllowSpaceOnly スペースのみの入力を許可する場合は真を指定する
     * @return bool 文字列が入力されていれば真
     */
    public static function hasValue(?string $value, bool $isAllowSpaceOnly = false): bool
    {
        if ($isAllowSpaceOnly !== true) {
            $value = str_replace([' ', '　'], '', $value);
        }
        return !is_null($value) && $value !== '';
    }
}