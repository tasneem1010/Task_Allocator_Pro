<?php

define('HostName', 'localhost');
define('DataBaseName', 'web1220446_db');
define('DPassword', 'Parasyte1');
define('UserName', 'web1220446_dbuser');

try {

    $connection = "mysql:host=" . HostName . ";dbname=" . DataBaseName;
    $pdo = new PDO($connection, UserName, DPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die($e->getMessage());
}
?>