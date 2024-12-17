<?php

include("../config.php");
include("scripts/variables.php");

echo "Hello there!";

try {

    import_ships($jsonShips);
    import_planets($jsonPlanets);

} catch (PDOException $e) {
    echo e->getMessage();
}
