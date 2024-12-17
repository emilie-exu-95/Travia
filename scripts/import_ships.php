<?php

use Php\Ship;

include("../config.php");

function import_ships(string $json)
{
    global $dbh;
    $jsonData = json_decode(file_get_contents($json), true);

    try {
        // Begin transaction
        $dbh->beginTransaction();

        // Empty table before filling
        $dbh->execute("TRUNCATE TABLE TRAVIA_Ship;");

        // Prepare statement for insert
        $stmt = $dbh->prepare(
            "INSERT INTO ship (id, name, camp, speed_kmh, capacity)
             VALUES (:id, :name, :camp, :speed_kmh, :capacity);"
        );

        // Iterate json file to create ship (object)
        foreach ($jsonData as $shipData) {
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
