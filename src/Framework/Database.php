<?php

declare(strict_types=1);

namespace Framework;

use PDO, PDOException, PDOStatement;

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
    private PDOStatement $statement;

    public function __construct(string $driver, array $config, string $username = 'root', string $password = '') {

        $config = http_build_query(data: $config, arg_separator: ';');
        
        $dsn = "{$driver}:{$config}";
        
        try
        {
            $this->connection = new PDO($dsn, $username, $password,[
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        }
        catch(PDOException $e){
            die("Unable to connect to DB");
        }
        
    }

    public function query(string $query, array $params = []){
        $this->statement = $this->connection->prepare($query);

        $this->statement->execute($params);
        return $this;
    }

    public function count(){
        return $this->statement->fetchColumn();
    }

    public function find(int $mode = PDO::FETCH_DEFAULT){
        return $this->statement->fetch($mode);
    }

    public function id(){
        return $this->connection->lastInsertId();
    }

    public function findAll(){
        return $this->statement->fetchAll();
    }
}