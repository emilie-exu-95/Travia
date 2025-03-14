<?php

    $submitErrors = array();
    $firstname = "";
    $lastname = "";
    $email = "";
    $password = "";

    if( isset($_POST["fname"]) || isset($_POST["lname"]) || isset($_POST["email"]) || isset($_POST["pass"]) ) {

        // Empty fields
        $submitErrors[] = "emptyFields";

        // Verify firstname
        if ( !(isset($_POST["fname"])
            && preg_match("/^[a-zA-Z]+$/", $_POST["fname"])
            && isset($_POST["lname"])
            && preg_match("/^[a-zA-Z]+$/", $_POST["name"])) ) {
            // Valid firstname and lastname -> contains letters only
            $firstname = $_POST["fname"];
            $lastname = $_POST["lname"];
        } else {
            // Defined or empty or invalid
            $submitErrors[] = "invalidNames";
        }


    } else {
        echo "";
    }