<?php

use Php\Ship;
include("../config.php");

function import_ships(string $json)
{
    $jsonData = json_decode(file_get_contents($json), true);
    global $dbh;
    foreach ($jsonData as $shipData) {
        //truncate table
        $truncate = "TRUNCATE TABLE ships;";
        $stmt = $dbh->prepare($truncate);
        $stmt->execute();
        echo "Table 'ship' has been emptied.";
        //create ship -> valid field data
        $ship = new Ship(
            $shipData["id"],
            $shipData["name"],
            $shipData["camp"],
            $shipData["speed_kmh"],
            $shipData["capacity"]
        );
        //import valid ship to database
        import_ship_to_database($ship);
    }
}

function import_ship_to_database(Ship $ship) {
    global $dbh;
    try {
        $insert = $dbh->prepare("INSERT INTO ship(id, name, camp, speed_kmh, capacity) VALUES(:id, :name, :camp, :speed_kmh, :capacity);");
        $stmt = $dbh->prepare($insert);
        $stmt->bindParam(":id", $ship["id"], PDO::PARAM_INT);
        $stmt->bindParam(":name", $ship["name"], PDO::PARAM_STR);
        $stmt->bindParam(":camp", $ship["camp"], PDO::PARAM_STR);
        $stmt->bindParam(":speed_kmh", $ship["speed_kmh"], PDO::PARAM_INT);
        $stmt->bindParam(":capacity", $ship["capacity"], PDO::PARAM_INT);
        $stmt->execute();
        echo "Ship " . $ship["id"] . " has been imported.";
    } catch (PDOException $e) {
        echo "Failed to insert ship in database: " . $e->getMessage();
    }

}