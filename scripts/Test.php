<?php

include("../config.php");

$jsonShips = "https://tfressin.fr/cours/projet-travia-tour/ships.json";
$jsonPlanets = "https://tfressin.fr/cours/projet-travia-tour/planets_details.json";
import_ships($jsonShips);
