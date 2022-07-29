<?php

namespace Alura\Pdo\Infrastructure\Persistence;

use PDO;

class ConnectionCreator
{
    public static function createConnection(): PDO
    {
        $dbPath = __DIR__ . '/../../../banco.sqlite';
        $connecion = new PDO('sqlite:'.$dbPath);
        //$connecion -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); padão no php 8

        return $connecion;
    }
}