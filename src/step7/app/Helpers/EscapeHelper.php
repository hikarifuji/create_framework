<?php

declare(strict_types=1);

namespace App\Helpers;

/**
 * HTML特殊文字をHTMLエンティティに変換するビューヘルパー
 * @package App\Helpers
 */
class EscapeHelper
{
    /**
     * HTML特殊文字をHTMLエンティティに変換するビューヘルパー
     * @param string $value
     * @return string
     */
    public function escape(?string $value): string
    {
        if (is_null($value)) {
            return '';
        }
        return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5);
    }
}