<?php

declare(strict_types=1);


namespace App\Application\Movie\Dto;


final class MoviePage
{
    /**
     * @param MovieSummary[] $movies
     */
    public function __construct(
        public readonly array $movies,
        public readonly int $page,
        public readonly int $limit,
        public readonly int $total,
        public readonly int $totalPages
    ) {}
}
