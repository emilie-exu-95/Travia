<?php
/*
 * This file contains the following functions
 *
 * validPassword($password) -> verifies password strength
 *
 */

// Verify password strength, return true if password meets the criteria
function validPassword($password): bool {
    $passwordRegex = array(
        "/.{12,}/", // min length, 12 characters
        "/[A-Z]/", // uppercase letter
        "/[a-z]/", // lowercase letter
        "/[0-9]/", // number
        "/[^a-zA-Z0-9]/", // special character
    );
    foreach ($passwordRegex as $regex) {
         if (!preg_match($regex, $password)) {
             return false;
         }
    }
    return true;
}

// Return planet id if planet exists, else return null
function getPlanetId(string $planetName): ?int {

    // Additional verifications
    if (strlen($planetName) > 50) {
        return null;
    }

    try {

        require "../utils/connection.php";

        // Prepare statement
        $stmt = $dbh->prepare(
            "SELECT id from TRAVIA_Planet WHERE LOWER(name) = LOWER(:planetName);"
        );

        // Bind value and execute
        $stmt->bindParam(":planetName", $planetName , PDO::PARAM_STR);
        $stmt->execute();

        // Fetch result and return id
        $planet = $stmt->fetch(PDO::FETCH_OBJ);
        return $planet ? (int)$planet->id : null;

    } catch(Exception $e) {
        echo "Error while searching planet in database : " . $e->getMessage(3);
        return null;
    }
}