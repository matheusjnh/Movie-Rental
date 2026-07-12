<?php

declare(strict_types=1);

use App\Infrastructure\Database\Mysql\MysqlConnectionFactory;
use App\Application\Movie\MovieRepository;
use App\Infrastructure\Persistence\Mysql\Movie\PdoMovieRepository;
use DI\ContainerBuilder;

use function DI\factory;
use function DI\autowire;

$databaseConfig = require_once __DIR__ . '/../config/database.php';

$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions([
    PDO::class => factory(
        static function () use ($databaseConfig): PDO {
            $factory = new MysqlConnectionFactory(
                host: $databaseConfig['host'],
                port: $databaseConfig['port'],
                database: $databaseConfig['database'],
                user: $databaseConfig['username'],
                password: $databaseConfig['password'],
            );

            return $factory->create();
        },
    ),

    MovieRepository::class => autowire(PdoMovieRepository::class),
]);

return $containerBuilder->build();
