<?php

class Database
{
    public function __construct(
        private string $hostName,
        private string $database,
        private string $userName,
        private string $password
    ) {
    }

    public function getConnection(): PDO
    {
        $dsn = "mysql:host={$this->hostName};dbname={$this->database}";

        return new PDO($dsn, $this->userName, $this->password, [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false
        ]);
    }
}
