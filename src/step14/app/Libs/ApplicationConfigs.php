<?php

declare(strict_types=1);

namespace App\Libs;

use App\Libs\Core\Traits\SingletonTrait;

/**
 * アプリケーション全体で共有する設定情報を保持するクラス
 * @package App\Libs
 */
class ApplicationConfigs
{
    /*
     * シングルトンクラスのためのトレイトを使う
     */
    use SingletonTrait;

    /**
     * @var IniFileParser iniファイルをパースするIniFileParserのインスタンス
     */
    private IniFileParser $parser;

    /**
     * 初期化処理
     */
    public function initialize()
    {
        $this->parser = (new IniFileParser(__DIR__ . '/../Config/application.ini'));
    }

    /**
     * すべての設定情報を返す
     */
    public function getAll(): array
    {
        return $this->parser->getAll();
    }

    /**
     * データベース接続情報を取得する
     */
    public function getDatabase(): array
    {
        return $this->parser->getBySection('Database');
    }

    /**
     * SMTP情報を取得する
     */
    public function getSmtp(): array
    {
        return $this->parser->getBySection('Smtp');
    }

    /**
     * 自動送信メールのFrom名称などのデフォルト記載情報を取得する
     */
    public function getMail(): array
    {
        return $this->parser->getBySection('Mail');
    }

    /**
     * アップロードディレクトリ、ログ出力ディレクトリなどのパス情報を取得する
     */
    public function getPaths(): array
    {
        return [
            'log' => realpath(__DIR__ . '/../../logs'),
            'upload' => realpath(__DIR__ . '/../../../upload'),
            'helpers' => realpath(__DIR__ . '/../Helpers'),
            'public' => realpath(__DIR__ . '/../../public'),
        ];
    }
}
