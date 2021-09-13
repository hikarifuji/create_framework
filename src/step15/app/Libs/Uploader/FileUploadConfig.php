<?php

declare(strict_types=1);

namespace App\Libs\Uploader;

/**
 * ファイルアップロードの設定を保持するクラス
 * @package App\Libs\Uploader
 */
class FileUploadConfig
{
    /**
     * @var string 画像ファイルのname属性
     */
    private string $name;

    /**
     * @var string 画像ファイルのラベル
     */
    private string $label;

    /**
     * @var array 許可する拡張子
     */
    private array $extensions = [];

    /**
     * @var array 許可するMIMEタイプ
     */
    private array $mimeTypes = [];

    /**
     * @var int サイズ上限 (bytes)
     */
    private int $maxBytes;

    /**
     * @var string アップロード後の拡張子無しのファイル名。
     */
    private string $newName;

    /**
     * @var string 移動先ファイルパス ex) C:/temp
     */
    private string $destination;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return array
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    /**
     * @param array $extensions
     */
    public function setExtensions(array $extensions): void
    {
        $this->extensions = $extensions;
    }

    /**
     * @return array
     */
    public function getMimeTypes(): array
    {
        return $this->mimeTypes;
    }

    /**
     * @param array $mimeTypes
     */
    public function setMimeTypes(array $mimeTypes): void
    {
        $this->mimeTypes = $mimeTypes;
    }

    /**
     * @return int
     */
    public function getMaxBytes(): int
    {
        return $this->maxBytes;
    }

    /**
     * @param int $maxBytes
     */
    public function setMaxBytes(int $maxBytes): void
    {
        $this->maxBytes = $maxBytes;
    }

    /**
     * @return string
     */
    public function getNewName(): string
    {
        return $this->newName;
    }

    /**
     * @param string $newName
     */
    public function setNewName(string $newName): void
    {
        $this->newName = $newName;
    }

    /**
     * @return string
     */
    public function getDestination(): string
    {
        return $this->destination;
    }

    /**
     * @param string $destination
     */
    public function setDestination(string $destination): void
    {
        $this->destination = $destination;
    }
}
