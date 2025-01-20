<?php

    include("import_planets.php");
    include("import_ships.php");
    include("variables.php");

    $time_start = microtime(true);
    echo "Execution started and ongoing... <br>";

    if ( isset($_POST["update"]) ) {
        $update = $_POST["update"];
        switch ($update) {
            case "update-planets":
                import_planets($jsonPlanets);
                break;
            case "update-ships":
                import_ships($jsonShips);
                break;
            default:
                echo "<br>Invalid action specified.<br>";
                break;
        }
    } else {
        echo "<br>No action specified.<br>";
    }

    echo "<br>Execution time in seconds : " . (microtime(true) - $time_start);