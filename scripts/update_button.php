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
                echo "Planets succesfully updated.<br>";
                break;
            case "update-ships":
                import_ships($jsonShips);
                echo "Ships successfully updated.<br>";
                break;
            default:
                echo "Invalid action specified.<br>";
                break;
        }
    } else {
        echo "No action specified.<br>";
    }

    echo "Execution time in seconds : " . (microtime(true) - $time_start);