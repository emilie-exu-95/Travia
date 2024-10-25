<?php

namespace Php;

class Ship {
    public $id;
    public $name;
    public $camp;
    public $speed_kmh;
    public $capacity;

    function __construct($id, $name, $camp, $speed_kmh, $capacity) {
        $this->id = $id;
        $this->name = $name;
        $this->camp = $camp;
        $this->speed_kmh = $speed_kmh;
        $this->capacity = $capacity;
    }

    function __toString() {
        echo "id: ".$this->id."\n"."name: ".$this->name."\n" . "camp: " . $this->camp . "\n" . "speed_kmh";
    }
}