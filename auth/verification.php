<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Verify account</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" integrity="sha384-dpuaG1suU0eT09tx5plTaGMLBsfDLzUCCUXOY2j/LSvXYuG6Bqs43ALlhIqAJVRb" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../utils/utils.css">
</head>

<body class="center">

    <div class="small-bloc">

        <h1>Account verification</h1>

        <hr>

        <?php

        $token = $_GET['token'] ?? "";

        try {

            // Verify token in database
            require_once "../utils/connection.php";
            $stmt = $dbh->prepare(
                "SELECT user_id, label FROM TRAVIA_Verification
                 WHERE token = :token AND expiration_date > NOW();
                 LIMIT 1;"
            );
            $stmt->bindParam(":token", $token, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);

            // If valid token, update user status
            if ($result && $result->label == "Account creation") {

                // Update user status in database
                $stmt = $dbh->prepare("UPDATE TRAVIA_User SET verified = 1 WHERE id = :user_id;");
                $userId = $result->user_id;
                $stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
                $stmt->execute();
                echo "Account verified successfully.";
                echo  "<br>You can now <a class='a-link' href='../login.php'>log in</a>.";

                // Add password to database
                $stmt = $dbh->prepare("INSERT INTO TRAVIA_Password (user_id, password, date_time) VALUES (:user_id, (SELECT password from TRAVIA_User WHERE id = :user_id), NOW());");

                // Delete token from database
                /*
                $stmt = $dbh->prepare("DELETE FROM TRAVIA_Verification WHERE token = :token");
                $stmt->bindParam(":token", $token, PDO::PARAM_STR);
                $stmt->execute();
                */

                // Send email confirming validation
                require_once "../scripts/email.php";
                $objet = "Travia - Account verified";
                $body = "
                    <h1>Congratulations, your account has been verified!</h1>
                    <p>You can now log in and begin your journey in the galaxy : </p>
                        <a href='http://localhost/Travia/login.php'>http://localhost/Travia/login.php</a>
                "
                ;

            } else {
                echo "Link is invalid or expired.";
                echo  "<br>Please return to the <a class='a-link' href='index.php'>form</a> and try again.";
            }
        } catch (Exception $e) {
            echo "Error while verifying account : " . $e->getMessage();
            echo "An error occurred while trying to verify your account. Please try again later.";
        }

        ?>

    </div>



</body>

<?php include("../utils/footer.php"); ?>

</html>

