<?php

echo "Hello first!";

include("../config.php");

$jsonShips = "https://tfressin.fr/cours/projet-travia-tour/ships.json";
$jsonPlanets = "https://tfressin.fr/cours/projet-travia-tour/planets_details.json";

echo "Hello there!";

try {
    import_ships($jsonShips);
} catch (PDOException $e) {
    echo e->getMessage();
}
