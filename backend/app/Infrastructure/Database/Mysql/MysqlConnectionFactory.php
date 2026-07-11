<?php

namespace App\Infrastructure\Database\Mysql;

use PDO;

final class MysqlConnectionFactory
{
    private array $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    private string $host;
    private string $database;
    private string $user;
    private string $password;
    private string $port;


    public function __construct(string $host, string $port, string $database, string $user, string $password)
    {
        $this->host = $host;
        $this->database = $database;
        $this->user = $user;
        $this->password = $password;
        $this->port = $port;
    }

    public function create(): PDO
    {
        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->database};charset=utf8mb4";

        return new PDO($dsn, $this->user, $this->password, $this->options);
    }
}
