<?php

// VERIFY INPUT VALIDITY

    if ( isset($_POST["origin"]) && isset($_POST["destination"]) ) {

        $origin = $_POST["origin"];
        $destination = $_POST["destination"];

        // Open file
        $planetsList = fopen("../files/planetsList", "r");
        if ( !$planetsList ) {
            echo "Error: Failure to open file.<br>";
        }

        // search planets in file
        $originFound = false;
        $destinationFound = false;
        while ( ($line=fgets($planetsList)) !== false ) {
            // Find origin
            if ( strpos($line, $$origin) ) {
                $originFound = true;
                echo "Origin found.<br>";
            }
            // Find destination
            if ( strpos($line, $destination) ) {
                echo "Destination found.<br>";
                $destinationFound = true;
            }
            // Both planets have been found
            if ( $originFound && $destinationFound ) {
                echo "Origin and Destination found.<br>";
                break;
            }
        }
        fclose($planetsList);

        // IF VALID INPUT, PROCEED
        if ( $originFound && $destinationFound ) {

            // Set cookies
            $time = 60 * 5;
            setcookie("origin", $origin, time() + $time);
            setcookie("destination", $destination, time() + $time);
            echo "Cookies have been set.<br>";


        } else {
            echo "Invalid planet(s).<br>";
        }
    } else { // Remove cookies, overwrite previous inputs
        setcookie("origin", "", time() - 60*5);
        setcookie("destination", "", time() - 60*5);
    }

    header("Location: index.php");
