<?php

declare(strict_types=1);

namespace App\Libs\Core;

use App\Libs\Core\Traits\SingletonTrait;

/**
 * PHPが保持する$_FILES変数のラッパークラス
 * @package App\Libs\Uploader
 */
class UploadedFile
{
    /**
     * @var string ブラウザから送信されたファイル名
     */
    private string $name;

    /**
     * @var string ブラウザから送信されたMIMEタイプ
     */
    private string $type;

    /**
     * @var string 仮保存されたファイル名
     */
    private string $temporaryName;

    /**
     * @var int エラーを表す数値(0のときはアップロード成功)
     */
    private int $error;

    /**
     * @var int ファイルサイズ(単位：バイト)
     */
    private int $size;

    /**
     * FileUploadInfo constructor.
     * @param string $itemName
     */
    public function __construct(string $itemName)
    {
        $this->name = $_FILES[$itemName]['name'];
        $this->type = $_FILES[$itemName]['type'];
        $this->temporaryName = $_FILES[$itemName]['tmp_name'];
        $this->error = intval($_FILES[$itemName]['error']);
        $this->size = intval($_FILES[$itemName]['size']);
    }

    /**
     * @return bool
     */
    public function isUploadedFile(): bool
    {
        return is_uploaded_file($this->temporaryName);
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->error === UPLOAD_ERR_OK;
    }

    /**
     * @return bool
     */
    public function hasSent(): bool
    {
        return $this->error !== UPLOAD_ERR_NO_FILE;
    }

    /**
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getTemporaryName(): string
    {
        return $this->temporaryName;
    }

    /**
     * @return mixed
     */
    public function getError(): int
    {
        return $this->error;
    }

    /**
     * @return mixed
     */
    public function getSize(): int
    {
        return $this->size;
    }
}
