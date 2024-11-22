<?php

include("../config.php");

function import_planets(string $json)
{
    global $dbh;
    $jsonData = json_decode(file_get_contents($json), true);

    try {
        // Begin transaction
        $dbh->beginTransaction();

        // Empty table before filling
        $dbh->execute("TRUNCATE TABLE planet;");

        // Prepare statement for insert
        $stmt = $dbh->prepare(
            "INSERT INTO planet (id, name, coord, x, y, subGridCoord, subGridX, subGridY, sunName region, sector, suns, moons, position, distance, dayLength, yearLength, diameter, gravity)
             VALUES (:id, :name, :coord, :x, :y, :subGridCoord, :subGridX, :subGridY, :sunName, regions, :sector, :suns, :moons, :position, :distance, :dayLength, :yearLeangth, :diameter, :gravity);"
        );

        // Iterate json file to create planet (object)
        foreach ( $jsonData as $planetData ) {
            // If planet is successfully created, valid data
            $planet = new Planet(
                $planetData["Id"],
                $planetData["Name"],
                $planetData["Image"],
                $planetData["Coord"],
                $planetData["X"],
                $planetData["Y"],
                $planetData["SunName"],
                $planetData["Region"],
                $planetData["Sector"],
                $planetData["Suns"],
                $planetData["Moons"],
                $planetData["Position"];
                $planetData["Distance"];
                $planetData["LengthDay"];
                $planetData["LengthYear"],
                $planetData["Diameter"],
                $planetData["Gravity"]
            );
            // Bind parameters and execute (data)
            $stmt->bindParam(":id", $planetData["Id"], PDO::PARAM_INT);
            $stmt->bindParam(":name", $planetData["Name"], PDO::PARAM_STR);
            $stmt->bindParam(":image", $planetData["Image"], PDO::PARAM_STR);
            $stmt->bindParam(":coord", $planetData["Coord"], PDO::PARAM_STR);
            $stmt->bindParam(":x", $planetData["X"], PDO::PARAM_INT);
            $stmt->bindParam(":y", $planetData["Y"], PDO::PARAM_INT);
            $stmt->bindParam(":subGridCoord", $planetData["SubGridCoord"], PDO::PARAM_STR);
            $stmt->bindParam(":subGridX", $planetData["SubGridY"], PDO::PARAM_STR);  // Treated as float
            $stmt->bindParam(":subGridY", $planetData["SubGridY"], PDO::PARAM_STR); // Treated as float
            $stmt->bindParam(":sunName", $planetData["SunName"], PDO::PARAM_STR);
            $stmt->bindParam(":region", $planetData["Region"], PDO::PARAM_STR);
            $stmt->bindParam(":sector", $planetData["Sector"], PDO::PARAM_STR);
            $stmt->bindParam(":suns", $planetData["Suns"], PDO::PARAM_INT);
            $stmt->bindParam(":moons", $planetData["Moons"], PDO::PARAM_INT);
            $stmt->bindParam(":position", $planetData["Position"], PDO::PARAM_INT);
            $stmt->bindParam(":distance", $planetData["Distance"], PDO::PARAM_INT);
            $stmt->bindParam(":dayLength", $planetData["LengthDay"], PDO::PARAM_INT);
            $stmt->bindParam(":yearLength", $planetData["LengthYear"], PDO::PARAM_INT);
            $stmt->bindParam(":diameter", $planetData["Diameter"], PDO::PARAM_INT);
            $stmt->bindParam(":gravity", $planetData["Gravity"], PDO::PARAM_INT);
            // Set unused object reference to null for garbage collector
            $planet = null;
        }

        // Commit transaction + trigger garbage collection
        $dbh->commit();
        gc_collect_cycles();

    } catch (PDOException $e) {
        $dbh->rollBack();
        echo "Error while importing planet to database : " . $e->getMessage();
    }
}