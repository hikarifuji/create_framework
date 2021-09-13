<?php

declare(strict_types=1);

namespace App\Helpers;

/**
 * セレクトボックスを生成するビューヘルパー
 * @package App\Helpers
 */
class FormSelectHelper
{
    /**
     * セレクトボックスを表す<select>タグを生成する。
     * @param string $name フォーム名
     * @param $default デフォルト選択値
     * @param array|null $attributes 追加したい属性名と属性値の連想配列
     * @param array|null $selection オプション名とオプション値の連想配列
     * @param bool $hasEmpty 先頭のオプションを空にしたいときに真を指定する
     * @return string 生成された<select>タグ
     */
    public function formSelect(string $name, $default, ?array $attributes, ?array $selection, bool $hasEmpty = true): string
    {
        $html = '';
        $attributesAsString = $this->generateAttributes($attributes);
        $html .= "<select name='{$name}' {$attributesAsString}>";
        if ($hasEmpty) {
            $html .= "<option value=''></option>";
        }
        foreach ($selection as $value => $label) {
            $selected = $default == $value ? 'selected' : '';
            $html .= "<option value='{$value}' {$selected}>{$label}</option>";
        }
        $html .= "</select>";
        return $html;
    }

    /**
     * 属性名と属性値の連想配列をHTML表現にして返す
     * @param array|null $attributes 属性名と属性値の連想配列
     * @return string 生成された文字列
     */
    private function generateAttributes(?array $attributes): string
    {
        $attributesList = [];
        foreach ($attributes as $name => $value) {
            $attributesList[] = "{$name}='{$value}'";
        }
        $attributesAsString = implode(' ', $attributesList);
        return $attributesAsString;
    }
}
