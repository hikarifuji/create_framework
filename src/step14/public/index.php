<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$handleException = require __DIR__ . '/../app/Core/handle-exception.php';
$handleException();

$container = require __DIR__ . '/../app/Core/container.php';
$container();

$routes = require __DIR__ . '/../app/Core/routes.php';
$routes();
