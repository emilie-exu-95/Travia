<?php

//IMPORTS
use Php\Ship;

function import_ships($jsonFile)
{
    $ships = [];
    $jsonData = json_decode($jsonFile, true);
    foreach ($jsonData as $data) {
        $ship = new Ship(
            $data['id'],
            $data['name'],
            $data['camp'],
            $data['speed_kmh'],
            $data['capacity']
        );
        $ships[] = $ship;
    }
    return $ships;
}

function import_planets($jsonFile)
{
    $planets = [];
    $jsonFile = json_decode($jsonFile, true);

}

?>