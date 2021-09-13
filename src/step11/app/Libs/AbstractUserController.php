<?php
declare(strict_types=1);

namespace App\Libs;

use App\Libs\Core\View;
use App\Libs\Core\Container;
use App\Libs\Core\Exception\ForbiddenException;

/**
 * 公開ページ用のコントローラクラス
 * @package App\Libs
 */
abstract class AbstractUserController extends Core\AbstractController
{
    /**
     * デフォルトのレイアウトファイルパス
     */
    protected const DEFAULT_LAYOUT_PATH = __DIR__ . '/../Modules/User/Layouts/layout.html';

    /**
     * @var bool ビューを使うかを表す真偽値。ImageControllerのように、HTML以外の形式で出力したいときは偽を指定する。
     */
    protected bool $isUseView = true;

    /**
     * @var View Viewインスタンス
     */
    protected View $view;

    /**
     * @var UserSession UserSessionインスタンス
     */
    protected UserSession $session;

    /**
     * @var bool アクセス中のユーザがログイン状態であるか否かの真偽値
     */
    protected bool $isLogin;

    /**
     * AbstractUserController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->session = Container::getInstance()->get('user.session');
        $this->isLogin = $this->session->isLogin();
        if ($this->isUseView) {
            $this->view = $this->loadView();
            $this->view->isLogin = $this->isLogin;
        }
    }

    /**
     * 公開ページ用のビュー(View)クラスのインスタンスを返す
     * @return View
     */
    private function loadView(): View
    {
        $uri = preg_replace('/^\//', '', $_SERVER['REQUEST_URI']);
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);
        $uriPaths = explode('/', $uri);
        $controller = isset($uriPaths[0]) && $uriPaths[0] ? basename($uriPaths[0]) : 'index';
        $method = isset($uriPaths[1]) && $uriPaths[1] ? basename($uriPaths[1]) : 'index';
        $templatePath = realpath(__DIR__ . "/../Modules/User/Views/{$controller}/{$method}.html");
        $view = new View($templatePath, self::DEFAULT_LAYOUT_PATH);
        return $view;
    }

    protected function checkCsrfToken(string $tokenName = 'csrf_token'): void
    {
        if ($this->session->checkCsrfToken($this->request->byPost('csrf_token')) !== true) {
            throw new ForbiddenException('CSRFチェックエラー');
        }
    }
}
