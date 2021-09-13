<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Libs\AbstractUserController;
use App\Libs\ImageManipulator;
use App\Models\ArticleModel;

/**
 * 画像表示に関連するコントローラー
 * @package App\Controllers
 */
class ImageController extends AbstractUserController
{
    /**
     * ImageController constructor.
     */
    public function __construct()
    {
        // ビューを使わない
        $this->isUseView = false;
        parent::__construct();
    }

    /**
     * 記事投稿確認ページでの、画像表示処理を行う
     */
    public function confirmAction(): void
    {
        $photoNumber = $this->request->byGetAsInt('num');
        $file = $this->session->get('photo' . $photoNumber);
        if ($file && file_exists($file)) {
            $image = new ImageManipulator($file);
            header('Content-Length: ' . filesize($file));
            header('Content-Type: ' . $image->getMimeType());
            readfile($file);
        }
    }

    /**
     * 記事投稿後の画像表示処理を行う
     */
    public function showAction(): void
    {
        $photoNumber = $this->request->byGetAsInt('num');
        $articleId = $this->request->byGetAsInt('article');
        if (is_null($articleId) || is_null($photoNumber)) {
            return;
        }
        $model = new ArticleModel();
        $article = $model->findOne($articleId);
        if (!$article['id']) {
            return;
        }
        $photoPath = $model->buildPhotoPath($articleId, $article['photo' . $photoNumber]);
        if ($photoPath) {
            $image = new ImageManipulator($photoPath);
            header('Content-Length: ' . filesize($photoPath));
            header('Content-Type: ' . $image->getMimeType());
            readfile($photoPath);
        }
    }
}