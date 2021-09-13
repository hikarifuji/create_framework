<?php

declare(strict_types=1);

namespace App\Libs\Uploader;

use App\Libs\Core\UploadedFile;

/**
 * ファイルアップロードを行うクラス
 * @package App\Libs\Uploader
 */
class FileUploader
{
    /**
     * @var string アップロード失敗時のエラーメッセージ
     */
    private string $errorMessage;

    /**
     * @var FileUploadConfig アップロードの設定情報
     */
    private FileUploadConfig $uploadConfig;

    /**
     * @var UploadedFile アップロードされたファイルの情報
     */
    private UploadedFile $uploadedFile;

    /**
     * コンストラクタ
     * @param FileUploadConfig $config アップロードの設定情報
     */
    public function __construct(FileUploadConfig $uploadConfig)
    {
        $this->uploadConfig = $uploadConfig;
        $this->uploadedFile = new UploadedFile($this->uploadConfig->getName());
    }

    /**
     * バリデーション失敗時のエラーメッセージを取得する
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * アップロードファイルの妥当性をチェックする
     * @param UploadedFile $uploadInfo
     * @return bool チェック成功なら真、チェック失敗なら偽
     */
    public function validate(): bool
    {
        // そもそもアップロードされていないときはスルー
        if ($this->uploadedFile->hasSent() !== true) {
            return true;
        }
        // PHPによるエラーを確認する
        if ($this->uploadedFile->isSuccess() !== true) {
            $this->errorMessage = $this->uploadConfig->getLabel() . '：アップロードエラーを検出しました。';
            return false;
        }
        // ファイル名から拡張子をチェックする
        $extension = pathinfo($this->uploadedFile->getName(), PATHINFO_EXTENSION);
        if (!in_array(strtolower($extension), $this->uploadConfig->getExtensions())) {
            $this->errorMessage = $this->uploadConfig->getLabel() . '：許可されていないファイル形式です。';
            return false;
        }
        // MIMEタイプをチェックする
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $this->uploadedFile->getTemporaryName());
        finfo_close($finfo);
        if (!in_array($mime, $this->uploadConfig->getMimeTypes())) {
            $this->errorMessage = $this->uploadConfig->getLabel() . '：許可されていないファイル形式です。';
            return false;
        }
        // ファイルサイズをチェックする
        if (filesize($this->uploadedFile->getTemporaryName()) > $this->uploadConfig->getMaxBytes()) {
            $this->errorMessage = $this->uploadConfig->getLabel() . '：ファイルサイズを超過しています。';
            return false;
        }
        return true;
    }

    /**
     * ファイルをアップロードする
     * @return bool|string アップロード成功時は保存されたファイルパス。失敗時はfalse
     */
    public function upload()
    {
        $extension = pathinfo($this->uploadedFile->getName(), PATHINFO_EXTENSION);
        $destinationPath = $this->uploadConfig->getDestination() . '/' . $this->uploadConfig->getNewName() . '.' . $extension;
        $uploaded = move_uploaded_file($this->uploadedFile->getTemporaryName(), $destinationPath);
        if ($uploaded) {
            return $destinationPath;
        }
        return false;
    }
}
