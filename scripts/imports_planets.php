<?php

function import_planets(string $json)
{
    global $dbh;
    $jsonData = json_decode(file_get_contents($json), true);

    try {
        // Begin transaction
        $dbh->beginTransaction();

        // Empty table before filling
    }
}