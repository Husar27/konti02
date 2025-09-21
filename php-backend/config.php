<?php
// config.php
// Skonfiguruj te dane zgodnie z danymi MySQL z vh.pl
$host = 'localhost';
$db   = 'nazwa_bazy';
$user = 'nazwa_uzytkownika';
$pass = 'haslo';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
