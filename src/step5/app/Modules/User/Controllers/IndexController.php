<?php

declare(strict_types=1);

namespace App\Controllers;

/**
 * TOPページのコントローラー
 * @package App\Controllers
 */
class IndexController
{
    /**
     * IndexController constructor.
     */
    public function __construct()
    {
    }

    /**
     * TOPページのコントロール処理を行う
     */
    public function indexAction(): void
    {
        echo 'IndexController::indexAction()がコールされました。';
    }
}