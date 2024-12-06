<?php

    include("import_planets.php");
    include("import_ships.php");

    if ( isset($GET["action"]) ) {
        $update = $_GET["action"];
        switch ($update) {
            case "update-planets":
                import_planets("https://tfressin.fr/cours/projet-travia-tour/planets_details.json");
                break;
            case "update-ships":
                import_ships("https://tfressin.fr/cours/projet-travia-tour/ships.json");
                break;
        }
    }