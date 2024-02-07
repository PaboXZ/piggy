<?php

require __DIR__ . '/src/Framework/Database.php';
use Framework\Database;

$driver = 'mysql';
$config = [
    'host' => 'localhost',
    'port' => 3306,
    'dbname' => 'phpiggy'
];

$db = new Database($driver, $config);

$sqlFile = file_get_contents("./database.sql");

$stmt = $db->query($sqlFile);
