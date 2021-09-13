<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Libs\AbstractUserController;

/**
 * TOPページのコントローラー
 * @package App\Controllers
 */
class IndexController extends AbstractUserController
{
    /**
     * IndexController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * TOPページのコントロール処理を行う
     */
    public function indexAction(): void
    {
        $this->view->layoutTitle = 'TOPページ';
        $this->view->message = 'ようこそ！';
        echo $this->view->render();
    }
}