<?php

declare(strict_types=1);

namespace App\Libs;

/**
 * 管理画面用のコントローラクラス
 * @package App\Libs
 */
abstract class AbstractAdminController extends Core\AbstractController
{
    /**
     * AbstractAdminController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }
}