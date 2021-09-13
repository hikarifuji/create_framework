<?php

declare(strict_types=1);

namespace App\Libs;

/**
 * iniファイルをパースするクラス
 */
class IniFileParser
{
    /**
     * パース対象のiniファイルパス
     */
    private string $filePath;

    /**
     * iniファイルのパース結果。パース失敗時はfalseとなる。
     */
    private array|false $parsedData;

    /**
     * IniFileParser constructor.
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $this->parsedData = parse_ini_file($this->filePath, true);
    }

    /**
     * パース結果の全データを取得する
     * @return array|false パース結果のデータ。パース失敗時はfalse
     */
    public function getAll()
    {
        return $this->parsedData;
    }

    /**
     * パース結果のうち、指定されたセクションの結果のみを取得する
     * @param string $section セクション名
     * @return array パース結果のうち、指定されたセクションのデータ
     */
    public function getBySection(string $section): array
    {
        return $this->parsedData[$section];
    }

    /**
     * パース結果のうち、指定されたセクションおよびキーの結果のみを取得する
     * @param string $section セクション名
     * @param string $key キー
     * @return array パース結果のうち、指定されたセクション・キーのデータ
     */
    public function getByKey(string $section, string $key): array
    {
        return $this->parsedData[$section][$key];
    }
}