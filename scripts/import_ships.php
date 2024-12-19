<?php

use Travia\Classes\Ship;
require_once "../class/Ship.php";

function import_ships(string $json)
{
    // Import data from json file
    $jsonData = json_decode(file_get_contents($json), true);

    // Connection to database
    include("../connection.php");

    $count = 1;

    try {
        // Begin transaction
        $dbh->beginTransaction();

        // Empty table before filling
        $dbh->exec("TRUNCATE TABLE TRAVIA_Ship;");

        // Prepare statement for insert
        $stmt = $dbh->prepare(
            "INSERT INTO TRAVIA_Ship (id, name, camp, speed_kmh, capacity)
             VALUES (:id, :name, :camp, :speed_kmh, :capacity);"
        );

        // Iterate json file to create ship (object)
        foreach ($jsonData as $shipData) {
            echo $count . " ";
            $count++;
            // If ship is sucessfully created, valid data
            $ship = new Ship(
                $shipData["id"],
                $shipData["name"],
                $shipData["camp"],
                $shipData["speed_kmh"],
                $shipData["capacity"]
            );
            // Bind parameters and execute (data)
            $stmt->bindParam(":id", $shipData["id"], PDO::PARAM_INT);
            $stmt->bindParam(":name", $shipData["name"], PDO::PARAM_STR);
            $stmt->bindParam(":camp", $shipData["camp"], PDO::PARAM_STR);
            $stmt->bindParam(":speed_kmh", $shipData["speed_kmh"], PDO::PARAM_INT);
            $stmt->bindParam(":capacity", $shipData["capacity"], PDO::PARAM_INT);
            $stmt->execute();

            // Set unused object reference to null for garbage collector
            $ship = null;
        }

        // Commit transaction + trigger garbage collection
        $dbh->commit();
        gc_collect_cycles();

    } catch (PDOException $e) {
        $dbh->rollBack();
        echo "Error while importing ship to database : " . $e->getMessage();
    }
}
