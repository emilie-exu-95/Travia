<?php

use Travia\Classes\Database;
require_once "../class/Database.php";
require "config.php";

static $dbh;

if (!isset($dbh)) {
    $dbh = new Database($host, $dbname, $username, $password);
    $dbh = $dbh->getConnection();
}
