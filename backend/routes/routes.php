<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use App\Application\Movie\Usecase\ListMovies;

return static function (App $app): void {
    $app->get('/health', function (
        ServerRequestInterface $request,
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

    $app->get('/health/database', function (
        ServerRequestInterface $request,
        ResponseInterface $response,
    ) use ($app) {
        /** @var PDO $pdo */
        $pdo = $app->getContainer()->get(PDO::class);

        $count = $pdo->query('SELECT COUNT(*) FROM film')->fetchColumn();

        $response->getBody()->write(json_encode(
            [
                'status' => 'ok',
                'films_count' => (int) $count,
            ],
            JSON_THROW_ON_ERROR
        ));

        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/movies', function (
        ServerRequestInterface $request,
        ResponseInterface $response,
    ) use ($app) {
        /** @var ListMovies $usecase */
        $usecase = $app->getContainer()->get(ListMovies::class);

        $params = $request->getQueryParams();

        $limit = 20;
        $page = 1;

        if (isset($params["limit"])) {
            $validLimit = filter_var($params["limit"], FILTER_VALIDATE_INT);

            if ($validLimit === false) {
                throw new InvalidArgumentException("Limit must be an integer");
            }

            $limit = $validLimit;
        }

        if (isset($params["page"])) {
            $validPage = filter_var($params["page"], FILTER_VALIDATE_INT);

            if ($validPage === false) {
                throw new InvalidArgumentException("Page must be an integer");
            }

            $page = $validPage;
        }

        $movies = $usecase->execute(page: $page, limit: $limit);

        $response->getBody()->write(json_encode($movies, JSON_THROW_ON_ERROR));
        return $response->withHeader('Content-Type', 'application/json');
    });
};
