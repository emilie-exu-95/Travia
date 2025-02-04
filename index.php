<!DOCTYPE html>

<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Travia</title>
    <link rel="icon" type="image/x-icon" href="">
    <link rel="stylesheet" href="search/index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" integrity="sha384-dpuaG1suU0eT09tx5plTaGMLBsfDLzUCCUXOY2j/LSvXYuG6Bqs43ALlhIqAJVRb" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="  crossorigin=""></script>

</head>

<?php

include("../header.html");
include("handle_planets.php");

?>
<body>

    <!-- Verify if origin and destination have been set -->
    <?php
    $planetsSet = false;
    if ( empty($_COOKIE["origin"]) || empty($_COOKIE["destination"]) ) {
        $origin = $_COOKIE["origin"];
        $destination = $_COOKIE["destination"];
        $planetsSet = true;
    }
    ?>

    <div class="window horizontal-alignment horizontal-center vertical-center">

        <!-- LEFT VERTICAL DIV -->
        <div class="left-div vertical-alignment horizontal-center">

            <!-- LOGO + ICON -->
            <div class="logo">
                <h1 class="text-light">TRAVIA</h1>
            </div>

            <!-- FORM (origin, destination)-->
            <form class="vertical-alignment" method="post" action="search/handle_planets.php">
                <!-- origin input-->
                <div class="input-bloc vertical-alignment text-light mb-3">
                    <input name="origin" type="text" id="origin" placeholder="e.g. Alderaan" required>
                    <label for="origin">Origin</label>
                </div>
                <!-- destination input-->
                <div class="input-bloc vertical-alignment text-light mb-3">
                    <input name="destination" type="text" id="destination" placeholder="e.g. Geonosis" required>
                    <label for="destination">Destination</label>
                </div>
                <!-- search button -->
                <button type="submit" class="blue-pink-gradient btn">Search</button>
            </form>

            <!-- IF COOKIES FOR ORIGIN AND DESTINATION SET, ADD THEM TO INPUT -->
            <?php
            if ( $planetsSet ) {
                echo "<script>";
                echo "document.getElementById('origin').value = " . $_COOKIE["origin"] ";";
                echo "document.getElementById('destination').value = " . $_COOKIE["destination"] . ";";
                echo "</script>";
            }

            ?>

            <!-- BUTTONS UPDATE PLANETS/SHIPS-->
            <!--
            <div class="update-imports vertical-alignment horizontal-center">
                <h5 class="separator-line-text">Imports</h5>

                <form class="vertical-alignment" action="../scripts/update_button.php" method="post">
                    <button class="btn update-button" type="submit" name="update" value="update-planets">Update Planets</button>
                    <button class="btn update-button" type="submit" name="update" value="update-ships">Update Ships</button>
                </form>
            </div>
            -->

        </div>

        <!-- RESULT DIV -->
        <div class="result-div">

        </div>

        <!-- MAP DIV -->
        <div class="map-div">

            <!-- CREATE THE MAP -->
            <script>
                // Store colors associated to Region
                const colors = new Map([
                    ["Colonies", "#fb787b"],
                    ["Core", "#e28607"],
                    ["Deep Core", "#c0b044"],
                    ["Expansion Region", "#8da315"],
                    ["Extragalactic", "#8da315"],
                    ["Hutt Space", "#8da315"],
                    ["Inner Rim Territories", "#8da315"],
                    ["Mid Rim Territorries", "#8da315"],
                    ["Outer Rim Territories", "#8da315"],
                    ["Talcene Sector", "#8da315"],
                    ["The Centrality", "#cf74fc"],
                    ["Tingel Arm", "#cf74fc"],
                    ["Wild Space", "#fe62ae"]
                ]

                var map = L.map("map", {
                    crs: L.CRS.Simple,
                    minZoom: -2;
                    zoom: -1;
                });
            </script>

            <!-- RETRIEVE PLANET DATA -->
            <?php
            include("../utils/connection.php");
            $query = "SELECT name, image, coord, x, y, subGridX, subGridY, region, sector from TRAVIA_Planet;";
            $result = $dbh->query($query);
            while ( $line = $result->fetch(PDO::FETCH_OBJ) ) {
                $name = $line->name;
                $x = ($line->x + $line->subGridX) * 6;
                $Y = ($line->y + $line->subGridY) * 6;
                $region = $line->region;
                $radius = 1; // temporary

                //Add planet to map
                echo "<script>";
                echo "var circle = L.circle(["$y, $x"], { color: colors.get($region), fillColor: colors.get($region), fillOpacity: 1, radius: $radius}).addTo(map);";
                echo "var circle.bindPopup($name);";

                echo "</script>";
            }

            ?>


            <?php
            /* TODO add travel if origin and destination are set
            if ( $planetsSet ) {

            }
            */

            ?>


        </div>

    </div>

</body>

<footer>
    <script src="scripts.js"></script>
</footer>

</html>