<?php

namespace Travia\Classes;

use http\Exception\RuntimeException;

class Trip {
    private int $num; // Auto incremented
    private int $departurePlanet;
    private int $destinationPlanet;
    private string $day;
    private string $departureTime;
}