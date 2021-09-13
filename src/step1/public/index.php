<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $router) {
    $router->addRoute(['GET'], '/', function($params) {
        $controller = new App\Controllers\IndexController();
        $controller->indexAction();
    });
    $router->addRoute(['GET'], '/user', function($params) {
        $controller = new App\Controllers\UserController();
        $controller->indexAction();
    });
    $router->addRoute(['GET'], '/user/{user-id}', function($params) {
        $controller = new App\Controllers\UserController();
        $controller->showAction($params);
    });
});

// GETパラメータを除去する。
// たとえば、$uriが「/user?id=777」だったとき、「/user」のように変換する。
$uri = $_SERVER['REQUEST_URI'];
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$httpMethod = $_SERVER['REQUEST_METHOD'];
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo 'ページがみつかりません。';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        echo 'HTTPメソッドが許可されていません。';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $params = $routeInfo[2];
        echo $handler($params);
        break;
}