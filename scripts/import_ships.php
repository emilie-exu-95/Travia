<?php

use Travia\Classes\Ship;
require_once "../class/Ship.php";

function import_ships(string $json): void
{
    // Connection to database
    global $dbh;
    include("../utils/connection.php");

    // Import data from json file
    $jsonData = json_decode(file_get_contents($json), true);

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

        $count = 0;
        // Iterate json file to create ship (object)
        foreach ($jsonData as $shipData) {
            // If ship is successfully created, valid data
            $ship = new Ship(
                $shipData["id"],
                $shipData["name"],
                $shipData["camp"],
                $shipData["speed_kmh"],
                $shipData["capacity"]
            );

            // Counter
            $count++;
            if ( $count%50 == 0 ) {
                echo "<br>";
            }
            echo $count . " ";

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
        echo "<br>Ships successfully updated.<br>";
        gc_collect_cycles();

    } catch (Exception $e) {
        $dbh->rollBack();
        echo "Error while importing ships to database : " . $e->getMessage();
    }
}
