<?php

include("config.php");

try {
    // Database connection
    $dbh = new PDO("mysql:host=$servername; dbname=$dbname; charset=utf8", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    print("OK");
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}