<?php

use Php\Ship;

require "../class/Ship.php";
require "../scripts/imports.php";

$ship = new Ship(1, 1, 1, 1, 1);
echo $ship;

$ships = import_ships("../data_files/ships.json");

/*
foreach ($ships as $ship) {
    echo $ship . "\n";
}
*/

?>