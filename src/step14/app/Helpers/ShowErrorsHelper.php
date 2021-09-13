<?php
declare(strict_types=1);

namespace App\Helpers;

/**
 * エラーメッセージを表示するためのビューヘルパー
 * @package App\Helpers
 */
class ShowErrorsHelper
{
    /**
     * エラーメッセージを表示する
     * @param array|null $errors エラーメッセージの配列
     * @return string
     */
    public function showErrors(?array $errors): string
    {
        if (!isset($errors) || count($errors) <= 0) {
            return '';
        }
        $list = '<ul class="error-area">';
        foreach ($errors as $error) {
            $list .= '<li>' . $error . '</li>';
        }
        $list .= '</ul>';
        return $list;
    }
}