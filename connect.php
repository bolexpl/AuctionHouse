<?php
session_start();

function pretty_print($var)
{
    echo "<pre>".print_r($var, true)."</pre>";
}

$CONFIG = require_once 'config.php';

try {
    $pdo = new PDO("mysql:host={$CONFIG['host']};dbname={$CONFIG['database']};charset=utf8",
        $CONFIG['user'], $CONFIG['password'], [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

} catch (PDOException $e) {
    exit("Błąd bazy danych.");
}

