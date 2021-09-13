<?php

declare(strict_types=1);

namespace App\Helpers;

/**
 * 既存のURLのパラメータを追加または変更して返すビューヘルパー
 * ex:
 *     $url = 'http://example.com/test.php?id=1111&name=Yamada&type=3';
 *     $ret = addUrlParam(['type' => 2, 'debug' => 1], $url);
 *     の場合、$ret は：
 *     http://example.com/test.php?id=1111&name=Yamada&type=2&debug=1
 *     となる。
 * @package App\Helpers
 */
class AddUrlParamHelper
{
    /**
     * 既存のURLのパラメータを追加または変更して返す。
     * @param array $additionalParams 追加または変更したいGETパラメータを示す連想配列
     * @param string $url URL
     * @return string 変更後のURL
     *
     */
    public function addUrlParam(array $additionalParams, string $url): string
    {
        $urlElements = parse_url($url);
        $query = $urlElements['query'] ?? '';
        $url = str_replace('?' . $query, '', $url);
        parse_str($query, $params);
        foreach ($additionalParams as $additionalParamKey => $additionalParamValue) {
            $params[$additionalParamKey] = $additionalParamValue;
        }
        $newParamString = http_build_query($params);
        return $url . '?' . $newParamString;
    }
}