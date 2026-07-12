<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Mysql\Movie;

use App\Application\Movie\MovieRepository;
use App\Application\Movie\Dto\MovieSummary;
use App\Application\Movie\Dto\MoviePage;

use PDO;

final class PdoMovieRepository implements MovieRepository
{
    public function __construct(private readonly PDO $pdo) {}

    private function countMovies(): int
    {
        $query = 'SELECT COUNT(*) from film';

        $stmt = $this->pdo->query($query);

        return (int) $stmt->fetchColumn();
    }

    public function paginate(int $page, int $limit): MoviePage
    {
        $query = 'SELECT 
                    f.film_id AS id,
                    f.title,
                    f.release_year,
                    f.length AS duration,
                    f.rating,
                    l.name AS language,
                    COALESCE(categories.categories, JSON_ARRAY()) AS categories
                FROM film AS f
                INNER JOIN `language` AS l 
                    ON f.language_id = l.language_id
                LEFT JOIN (
                    SELECT
                        fc.film_id,
                        JSON_ARRAYAGG(c.name) AS categories
                    FROM film_category AS fc
                    INNER JOIN category AS c 
                        ON c.category_id = fc.category_id
                    GROUP BY fc.film_id
                ) AS categories
                    ON f.film_id = categories.film_id
                ORDER BY f.film_id ASC
                LIMIT :limit 
                OFFSET :offset';

        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', ($page - 1) * $limit, PDO::PARAM_INT);

        $stmt->execute();
        $rows = $stmt->fetchAll();

        $movies = [];

        foreach ($rows as $row) {
            /** @var string[] $categories */
            $categories = json_decode(
                $row['categories'],
                associative: true,
                flags: JSON_THROW_ON_ERROR
            );

            $movies[] = new MovieSummary(
                id: (int) $row['id'],
                title: $row['title'],
                releaseYear: $row['release_year'] !== null
                    ? (int) $row['release_year']
                    : null,
                durationInMinutes: $row['duration'] !== null
                    ? (int) $row['duration']
                    : null,
                rating: $row['rating'],
                language: $row['language'],
                categories: $categories
            );
        }

        $count = $this->countMovies();
        $totalPages = (int) ceil($count / $limit);

        return new MoviePage(
            movies: $movies,
            page: $page,
            limit: $limit,
            total: $count,
            totalPages: $totalPages
        );
    }
}
