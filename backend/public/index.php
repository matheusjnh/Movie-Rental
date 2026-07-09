<?php

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/health', function (Request $request, Response $response, array $args) {
    $data = ['status' => 'ok'];

    $response->getBody()->write(json_encode($data, JSON_THROW_ON_ERROR));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();
