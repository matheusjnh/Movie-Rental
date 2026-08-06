<?php

declare(strict_types=1);

use App\Presentation\Http\Controller\Movie\ListMoviesController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;

return static function (App $app): void {
    $app->get('/health', function (
        ServerRequestInterface $_request,
        ResponseInterface $response,
    ): ResponseInterface {
        $response->getBody()->write(
            json_encode(
                ['status' => 'ok'],
                JSON_THROW_ON_ERROR
            )
        );

        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/movies', ListMoviesController::class);
};
