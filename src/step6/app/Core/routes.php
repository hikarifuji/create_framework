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
        $router->addRoute(['GET', 'POST'], '/user', function($params) use ($run) {
            $run('UserController', 'indexAction', $params);
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