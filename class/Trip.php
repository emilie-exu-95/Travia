<?php

namespace Travia\Classes;

use http\Exception\RuntimeException;

class Trip {
    private int $num; // Auto incremented
    private int $departurePlanet; //planetId
    private int $destinationPlanet; //planetId
    private string $day;
    private string $departureTime;
    private  int $ship; // shipId
}