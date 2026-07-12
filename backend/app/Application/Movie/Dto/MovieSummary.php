<?php

declare(strict_types=1);


namespace App\Application\Movie\Dto;

class MovieSummary
{
    /** @param string[] $categories */
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly ?int $releaseYear,
        public readonly ?int $durationInMinutes,
        public readonly ?string $rating,
        public readonly string $language,
        public readonly array $categories,
    ) {}
}
