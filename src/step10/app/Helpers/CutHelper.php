<?php

declare(strict_types=1);

namespace App\Helpers;

/**
 * 長い文字列を丸めるビューヘルパー
 * @package App\Helpers
 */
class CutHelper
{
    /**
     * 長い文字列を丸める。
     * @param string $val 対象となる文字列
     * @param int $len 丸めたい長さ
     * @return string 丸め後の文字列
     */
    public function cut(string $val, int $len, string $trimMaker = '…'): string
    {
        $width = intval($len) * 2 + 1;
        return mb_strimwidth($val, 0, $width, $trimMaker, "UTF-8");
    }
}