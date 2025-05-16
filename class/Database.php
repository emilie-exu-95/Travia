<?php

namespace Travia\Classes;

use PDO;
use PDOException;

class Database {
    private string $host;
    private string $database;
    private string $username;
    private string $password;
    private ?PDO $pdo = null; // connexion

    public function __construct(string $host, string $database, string $username, string $password) {
        $this->host = $host;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
    }

    // Get connection
    public function getConnection() : ?PDO {
        // Set connection if not yet defined
        if ($this->pdo == null) {
            try {
                $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->database", $this->username, $this->password);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Error while connecting to database : " . $e->getMessage();
                return null;
            }
        }
        return $this->pdo;
    }

}