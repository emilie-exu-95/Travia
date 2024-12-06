<?php

include("../config.php");

$jsonShips = "https://tfressin.fr/cours/projet-travia-tour/ships.json";
$jsonPlanets = "https://tfressin.fr/cours/projet-travia-tour/planets_details.json";

echo "Hello there!";

try {

    import_ships($jsonShips);
    import_planets($jsonPlanets);

} catch (PDOException $e) {
    echo e->getMessage();
}
