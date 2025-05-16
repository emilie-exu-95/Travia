<?php

use Travia\Classes\Planet;
use Travia\Classes\Trip;

require_once "../class/Planet.php";
require_once "../class/Trip.php";

function import_planets(string $jsonFilePath)
{
    // Connection to database
    global $dbh;
    include("../utils/connection.php");

    // Import data from json file
    ini_set('memory_limit', '512M'); // Increase memory limit
    $jsonContent = file_get_contents($jsonFilePath);
    $jsonData = json_decode($jsonContent, true);
    if ($jsonData === null) {
        echo "Error : invalid json file";
        return;
    }

    try {

        // Empty tables before filling
        $dbh->exec("DELETE FROM TRAVIA_Planet;");
        $dbh->exec("DELETE FROM TRAVIA_Trip;");

        // Prepare statement for insert
        $stmt = $dbh->prepare(
            "INSERT INTO TRAVIA_Planet (id, name, image, coord, x, y, subGridCoord, subGridX, subGridY, sunName, region, sector, suns, moons, position, distance, dayLength, yearLength, diameter, gravity)
             VALUES (:id, :name, :image, :coord, :x, :y, :subGridCoord, :subGridX, :subGridY, :sunName, :region, :sector, :suns, :moons, :position, :distance, :dayLength, :yearLength, :diameter, :gravity);"
        );
        $stmt2 = $dbh->prepare(
            "INSERT INTO TRAVIA_Ship (departurePlanet, destinationPlanet, day, departureTime, ship)
            VALUES (:departurePlanet, :destinationPlanet, :day, :time, :ship);"
        );

        $count = 0;

        // Iterate over JSON file
        foreach ($jsonData as $planetData) {

            // PLANET : Set missing values
            $planetData["SunName"] ??= "";
            $planetData["Image"] ??= "";
            $planetData["SubGridCoord"] ??= "";
            $planetData["Coord"] ??= "";

            // PLANET : If planet is successfully created, data is valid
            $planetObject = new Planet(
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

            // PLANET : Bind values + add planet to database
            $stmt->bindParam(":id", $planetData["Id"], PDO::PARAM_INT);
            $stmt->bindParam(":name", $planetData["Name"], PDO::PARAM_STR);
            $stmt->bindParam(":image", $planetData["Image"], PDO::PARAM_STR);
            $stmt->bindParam(":coord", $planetData["Coord"], PDO::PARAM_STR);
            $stmt->bindParam(":x", $planetData["X"], PDO::PARAM_INT);
            $stmt->bindParam(":y", $planetData["Y"], PDO::PARAM_INT);
            $stmt->bindParam(":subGridCoord", $planetData["SubGridCoord"], PDO::PARAM_STR);
            $stmt->bindParam(":subGridX", $planetData["SubGridX"], PDO::PARAM_STR);  // processed as float
            $stmt->bindParam(":subGridY", $planetData["SubGridY"], PDO::PARAM_STR); // processed as float
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


            /*
            // TRIPS
            foreach ($planetData["trips"] as $day => $trip) {

                foreach ($trip as $tripData) {

                    // If trip is successfully set, data is valid
                    $tripObject = new Trip(
                        $planetData["Id"],
                        $tripData["destination_planet_id"][0],
                        $day,
                        $tripData["departure_time"][0],
                        $tripData["ship_id"][0]
                    );

                    // TRIP : Bind values + add trip to database
                    $stmt2->bindParam(":departurePlanet", $planetData["Id"], PDO::PARAM_INT);
                    $stmt2->bindParam(":destinationPlanet", $tripData["destination_planet_id"][0], PDO::PARAM_INT);
                    $stmt2->bindParam(":day", $day, PDO::PARAM_STR);
                    $stmt2->bindParam(":time", $tripData["time"][0], PDO::PARAM_STR);
                    $stmt2->bindParam(":ship", $tripData["ship_id"][0], PDO::PARAM_INT);
                    $stmt2->execute();
                }
            }
            */

            // Set unused object reference to null for garbage collection
            $planetObject = null;
            $tripObject = null;

            // Counter
            if ($count%50 == 0) { // Jump a line every 50 entries
                echo "<br>";
            }
            echo ++$count . " ";

        }

        echo "<br>Planets successfully updated.<br>";

        // Trigger garbage collection
        gc_collect_cycles();


    } catch (Exception $e) {
        echo "Error while importing planets to database : " . $e->getMessage();
    }
}