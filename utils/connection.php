<?php

global $servername, $dbname, $username, $password;
require("config.php");

try {

    // Database connection
    $dbh = new PDO("mysql:host=$servername; dbname=$dbname; charset=utf8", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    print("Connection to database successful.<br>");

} catch (PDOException $e) {
    echo "Error : " . $e->getMessage();
}

// Redirection to prevent access to this page
if (basename($_SERVER["PHP_SELF"]) == "connection.php") {
    header("Location: ../index.php");
}