<?php

declare(strict_types=1);

namespace App\Libs;

use Intervention\Image\ImageManager;
use Intervention\Image\Image;

/**
 * 画像を加工するクラス
 * @package App\Libs
 */
class ImageManipulator
{
    /**
     * @var string 対象画像ファイルパス
     */
    private string $filePath;

    /**
     * @var Image \Intervention\Image\Imageインスタンス。
     */
    private Image $image;

    /**
     * ImageManipulator constructor.
     * @param $filePath
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
        $manager = new ImageManager(array('driver' => 'gd'));
        $this->image = $manager->make($filePath);
    }

    /**
     * 対象画像のMIMEタイプ文字列を取得する
     * @return string MIMEタイプ文字列
     */
    public function getMimeType(): string
    {
        return $this->image->mime();
    }

    /**
     * 画像をリサイズする。
     * 幅のみ指定のときは、高さを自動調整する。
     * 高さのみ指定のときは、幅を自動調整する。
     * @param int|null $width リサイズ後の幅
     * @param int|null $height リサイズ後の高さ
     * @return $this 自身のインスタンス
     */
    public function resize(?int $width = null, ?int $height = null): ImageManipulator
    {
        if ($width === null && $height === null) {
            return $this;
        } elseif ($width !== null && $height !== null) {
            $this->image->resize($width, $height);
        } else {
            $this->image->resize(null, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        return $this;
    }

    /**
     * 画像をファイルに保存する
     * @param string|null $savePath 書き込み対象のファイルパス。nullの場合はコンストラクタで指定した自身のファイルパス。
     * @return string 書き込み結果のファイルパス
     */
    public function save(string $savePath = null): string
    {
        $savePath = $savePath ?? $this->filePath;
        $this->image->save($savePath);
        return $savePath;
    }
}