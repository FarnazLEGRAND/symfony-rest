<?php

namespace App\Repository;

class Database
{

    public static function getConnection()
    {
        return new \PDO("mysql:host=localhost:8889;dbname=symfony_rest", "root", "root");

    }
}