<?php

declare(strict_types=1);

namespace Framework;

use PDO, PDOException;

/*
* Database connection class.
*
* @param string $driver Driver for connection.
*
* @param array $config Array containing data for DSN.
*
* @param string $username Username [optional] default: root.
*
* @param string $password Password [optional] default: ''.
*/

class Database {

    private PDO $connection;

    public function __construct(string $driver, array $config, string $username = 'root', string $password = '') {

        $config = http_build_query(data: $config, arg_separator: ';');
        
        $dsn = "{$driver}:{$config}";
        
        try
        {
            $this->connection = new PDO($dsn, $username, $password);
        }
        catch(PDOException $e){
            die("Unable to connect to DB");
        }
        
    }

    public function query(string $query){
        $this->connection->query($query);
    }
}