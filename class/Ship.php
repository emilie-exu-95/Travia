<?php

namespace Travia\Classes;
class Ship
{
    private int $id;
    private string $name;
    private string $camp;
    private float $speed_kmh;
    private int $capacity;

    function __construct($id, $name, $camp, $speed_kmh, $capacity)
    {
        $this->id = $id;
        $this->name = $name;
        $this->camp = $camp;
        $this->speed_kmh = $speed_kmh;
        $this->capacity = $capacity;
    }

    function __toString()
    {
        return "id: " . $this->id . "\n" . "name: " . $this->name . "\n" . "camp: " . $this->camp . "\n" . "speed_kmh: " . $this->speed_kmh . "\n" . "capacity: " . $this->capacity;
    }
}