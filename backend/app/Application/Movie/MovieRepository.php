<?php

declare(strict_types=1);

namespace App\Application\Movie;

use App\Application\Movie\Dto\MoviePage;


interface MovieRepository
{
    public function paginate(int $page, int $limit): MoviePage;
}
