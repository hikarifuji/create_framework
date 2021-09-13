<?php

declare(strict_types=1);

return function () {

    $dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $router) {

        $run = function ($controller, $action, $params)
        {
            $class = "\\App\\Controllers\\{$controller}";
            $controller = new $class();
            $controller->{$action}($params);
        };

        $router->addRoute(['GET'], '/', function($params) use ($run){
            $run('IndexController', 'indexAction', $params);
        });
        $router->addRoute(['GET', 'POST'], '/user/start', function($params) use ($run) {
            $run('UserController', 'startAction', $params);
        });
        $router->addRoute(['GET', 'POST'], '/user/input-token', function($params) use ($run) {
            $run('UserController', 'inputTokenAction', $params);
        });
        $router->addRoute(['GET', 'POST'], '/user/create', function($params) use ($run) {
            $run('UserController', 'createAction', $params);
        });
        $router->addRoute(['GET', 'POST'], '/user/created', function($params) use ($run) {
            $run('UserController', 'createdAction', $params);
        });
        $router->addRoute(['GET', 'POST'], '/auth/login', function($params) use ($run) {
            $run('AuthController', 'loginAction', $params);
        });
        $router->addRoute(['GET'], '/auth/login-complete', function($params) use ($run) {
            $run('AuthController', 'loginCompleteAction', $params);
        });
        $router->addRoute(['GET'], '/auth/logout', function($params) use ($run) {
            $run('AuthController', 'logoutAction', $params);
        });
        $router->addRoute(['GET', 'POST'], '/post', function($params) use ($run) {
            $run('PostController', 'indexAction', $params);
        });
        $router->addRoute(['GET', 'POST'], '/post/confirm', function($params) use ($run) {
            $run('PostController', 'confirmAction', $params);
        });
        $router->addRoute(['POST'], '/post/complete', function($params) use ($run) {
            $run('PostController', 'completeAction', $params);
        });
        $router->addRoute(['GET'], '/image/confirm', function($params) use ($run) {
            $run('ImageController', 'confirmAction', $params);
        });
        $router->addRoute(['GET'], '/image/show', function($params) use ($run) {
            $run('ImageController', 'showAction', $params);
        });
    });

    // URIの末尾にあるGETパラメータを除去します。
    // たとえば、$uriが「/user/show?id=123」であったとき、以下の処理によって、$uriは「/user/show」に変わります。
    $uri = $_SERVER['REQUEST_URI'];
    if (false !== $pos = strpos($uri, '?')) {
        $uri = substr($uri, 0, $pos);
    }
    $uri = rawurldecode($uri);

    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            throw new \App\Libs\Core\Exception\PageNotFoundException();
            break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            throw new \App\Libs\Core\Exception\MethodNotAllowedException();
            break;
        case FastRoute\Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $params = $routeInfo[2];
            echo $handler($params);
            break;
    }
};