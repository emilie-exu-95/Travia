<?php

  $username = 'phpmyadmin';
  $database = 'phpmyadmin';
  $password = 'MyDatabasePassword';

try {
    $dbh = new PDO('mysql:host=localhost;dbname='.$database, $username, $password);
} catch (PDOException $e) {
    echo $e->getMessage();
}