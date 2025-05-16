<?php

    session_start();

    // If user is not logged in, redirect to login page
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
    } else {

        // Get default values for origin (home) and destination (work)
        require "utils/config.php";
        $stmt = $dbh->prepare("select home.name as home_planet, work.name as work_planet
            from TRAVIA_User user
            left join TRAVIA_Planet home on home.id = user.homePlanetId
            left join TRAVIA_Planet work on work.id = user.workPlanetId
            where user.id = :user_id;"
        );
        $stmt->bindParam(":user_id", $_SESSION['user'], PDO::PARAM_INT);
        $stmt->execute();

        // Store values in session
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        $_SESSION['home_planet'] = $result->home_planet;
        $_SESSION['work_planet'] = $result->work_planet;
    }

?>


<!DOCTYPE html>

<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Travia</title>
    <link rel="icon" type="image/x-icon" href="">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" integrity="sha384-dpuaG1suU0eT09tx5plTaGMLBsfDLzUCCUXOY2j/LSvXYuG6Bqs43ALlhIqAJVRb" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="  crossorigin=""></script>

</head>

<body>

<!-- If origin and destination are set, put them by default -->
<?php
    $origin = $_SESSION["home"] ?? "";
    $destination = $_SESSION["work"] ?? "";
?>

<div class="window horizontal-alignment horizontal-center vertical-center">

    <!-- LEFT VERTICAL DIV -->
    <div class="left-div vertical-alignment horizontal-center">

        <!-- LOGO + ICON -->
        <div class="logo">
            <h1 class="text-light">TRAVIA</h1>
        </div>

        <!-- FORM (origin, destination)-->
        <form class="vertical-alignment" method="post" action="handle_planets.php">
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

        <!-- BUTTONS UPDATE PLANETS/SHIPS-->
        <!--
        <div class="update-imports vertical-alignment horizontal-center">
            <h5 class="separator-line-text">Imports</h5>
            <form class="vertical-alignment" action="scripts/update_button.php" method="post">
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
        </script>

        <!-- RETRIEVE PLANET DATA -->
        <?php
        global $dbh;
        require_once("utils/connection.php");
        $query = "SELECT name, image, coord, x, y, subGridX, subGridY, region, sector from TRAVIA_Planet;";
        $result = $dbh->query($query);
        while ( $line = $result->fetch(PDO::FETCH_OBJ) ) {
            $name = $line->name;
            $x = ($line->x + $line->subGridX) * 6;
            $Y = ($line->y + $line->subGridY) * 6;
            $region = $line->region;
            $radius = 1; // temporary
        }

        ?>


    </div>

</div>

</body>

<?php include("utils/footer.php"); ?>

</html>