<?php

function import_planets(string $json) {
    $jsonData = json_decode(file_get_contents($json), true);
}