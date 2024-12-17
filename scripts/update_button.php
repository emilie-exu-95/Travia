<?php

    include("import_planets.php");
    include("import_ships.php");
    include("variables.php");

    if ( isset($GET["action"]) ) {
        $update = $_GET["action"];
        switch ($update) {
            case "update-planets":
                import_planets($jsonPlanets);
                break;
            case "update-ships":
                import_ships($sonShips);
                break;
        }
    }