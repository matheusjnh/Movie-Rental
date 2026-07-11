<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;

return static function (App $app): void {
    $app->get('/health', function (
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $response->getBody()->write(json_encode(['status' => 'ok'], JSON_THROW_ON_ERROR));

        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/health/database', function (
        ServerRequestInterface $request,
        ResponseInterface $response,
    ) use ($app) {
        /** @var PDO $pdo */
        $pdo = $app->getContainer()->get(PDO::class);

        $count = $pdo->query('SELECT COUNT(*) FROM film')->fetchColumn();

        $response->getBody()->write(json_encode([
            'status' => 'ok',
            'films_count' => (int) $count,
        ], JSON_THROW_ON_ERROR));

        return $response->withHeader('Content-Type', 'application/json');
    });
};
