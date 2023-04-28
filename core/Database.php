<?php

namespace App\Core;

use \PDO;

class DB
{
    /**
     * The PDO object used for connecting to the database.
     *
     * @var PDO
     */
    private static $pdo;

    /**
     * Connect to the database.
     *
     * @return void
     */
    private static function connect()
    {
        $string = DB_CONNECTION . ":host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        self::$pdo = new PDO($string, DB_USER, DB_PASS);
    }

    /**
     * Execute a SQL query.
     *
     * @param string $query The SQL query to execute.
     * @param array $data An array of parameters to bind to the query.
     * @return mixed The result of the query.
     */
    public static function query($query, $data = [])
    {
        // Open connection
        self::connect();

        // Execute a SQL query and fetch all results
        $stmt  = self::$pdo->prepare($query);
        $stmt->execute($data);

        // Close connection
        self::$pdo = null;

        return $stmt;
    }
}
