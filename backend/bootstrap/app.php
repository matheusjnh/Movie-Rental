<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

$container = require __DIR__ . '/container.php';

AppFactory::setContainer($container);

$app = AppFactory::create();

(require __DIR__ . '/../routes/routes.php')($app);
(require __DIR__ . '/error_middleware.php')($app);

return $app;
