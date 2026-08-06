<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controller\Movie;

use App\Application\Movie\Usecase\ListMovies;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ListMoviesController
{
    public function __construct(private readonly ListMovies $listMovies) {}

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
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

        $movies = $this->listMovies->execute(page: $page, limit: $limit);

        $response->getBody()->write(json_encode($movies, JSON_THROW_ON_ERROR));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
