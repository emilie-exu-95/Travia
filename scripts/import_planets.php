<?php

use Travia\Classes\Planet;
require_once "../class/Planet.php";

function import_planets(string $json)
{
    // Connection to database
    include("../utils/connection.php");

    echo "here";

    // Import data from json file
    //$jsonData = json_decode(file_get_contents($json), true);

    $jsonContent = file_get_contents($json);

    echo "<br>jsonContent ";

    // SOMEHOW NOT WORKING HERE
    $jsonData = json_decode($jsonContent, true);

    echo "<br>jsonData<br>";

    try {
        // Begin transaction
        $dbh->beginTransaction();

        // Empty table before filling
        $dbh->exec("TRUNCATE TABLE TRAVIA_Planet;");

        // Prepare statement for insert
        $stmt = $dbh->prepare(
            "INSERT INTO TRAVIA_Planet (id, name, image, coord, x, y, subGridCoord, subGridX, subGridY, sunName, region, sector, suns, moons, position, distance, dayLength, yearLength, diameter, gravity)
             VALUES (:id, :name, :image, :coord, :x, :y, :subGridCoord, :subGridX, :subGridY, :sunName, :region, :sector, :suns, :moons, :position, :distance, :dayLength, :yearLength, :diameter, :gravity);"
        );

        $count = 0;

        echo "one";
        // Iterate over JSON file
        foreach ($jsonData as $planetData) {

            // If planet is successfully created, valid data
            $planet = new Planet(
                $planetData["Id"],
                $planetData["Name"],
                $planetData["Image"],
                $planetData["Coord"],
                $planetData["X"],
                $planetData["Y"],
                $planetData["SubGridCoord"],
                $planetData["SubGridX"],
                $planetData["SubGridY"],
                $planetData["SunName"],
                $planetData["Region"],
                $planetData["Sector"],
                $planetData["Suns"],
                $planetData["Moons"],
                $planetData["Position"],
                $planetData["Distance"],
                $planetData["LengthDay"],
                $planetData["LengthYear"],
                $planetData["Diameter"],
                $planetData["Gravity"]
            );

            // Counter
            $count++;
            if ( $count%50 == 0 ) {
                echo "<br>";
            }
            echo $count . " ";

            // Set values if null
            $image = $planetData["Image"] ?? "";

            // Bind parameters and execute (data)
            $stmt->bindParam(":id", $planetData["Id"], PDO::PARAM_INT);
            $stmt->bindParam(":name", $planetData["Name"], PDO::PARAM_STR);
            $stmt->bindParam(":image", $image, PDO::PARAM_STR);
            $stmt->bindParam(":coord", $planetData["Coord"], PDO::PARAM_STR);
            $stmt->bindParam(":x", $planetData["X"], PDO::PARAM_INT);
            $stmt->bindParam(":y", $planetData["Y"], PDO::PARAM_INT);
            $stmt->bindParam(":subGridCoord", $planetData["SubGridCoord"], PDO::PARAM_STR);
            $stmt->bindParam(":subGridX", $planetData["SubGridX"], PDO::PARAM_STR);  // Treated as float
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
            $stmt->execute();

            // Set unused object reference to null for garbage collection
            $planet = null;
        }

        // Commit transaction + trigger garbage collection
        $dbh->commit();
        echo "<br>Planets succesfully updated.<br>";
        gc_collect_cycles();

        // Create file that will contain all planet names for searching purposes
        /*
        try {
            $planetFile = fopen("../files/planetsList.txt", "w");
            echo "Planets list update ongoing.<br>";
            $result = $dbh->query("SELECT name FROM TRAVIA_Planet;");
            while ( $line = $result->fetch(PDO::FETCH_OBJ) ) {
                fwrite($planetFile, $line->name . "\n");
            }
            fclose($planetFile);
            echo "Planets list succesfully updated.<br>"
        } catch (Exception $e) {
            echo "Echo while writing in file : " . $e->getMessage();
        }
        */


    } catch (Exception $e) {
        $dbh->rollBack();
        echo "Error while importing planets to database : " . $e->getMessage();
    }
}