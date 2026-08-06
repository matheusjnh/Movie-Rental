<?php

declare(strict_types=1);

namespace App\Application\Movie\Usecase;

use InvalidArgumentException;
use App\Application\Movie\MovieRepository;
use App\Application\Movie\Dto\MoviePage;

final class ListMovies
{
    public function __construct(private readonly MovieRepository $moviesRepository) {}

    public function execute(int $page = 1, int $limit = 20): MoviePage
    {
        if ($page <= 0) {
            throw new InvalidArgumentException("Page should be a positive integer");
        }

        if ($limit <= 0 || $limit > 100) {
            throw new InvalidArgumentException("Limit should be a positive integer between 1 and 100");
        }

        return $this->moviesRepository->paginate($page, $limit);
    }
}
