<?php

    // If user is not logged in, redirect to login page
    /*
    if (!isset($_SESSION['user'])) {
        header("Location: ../login.php");
    }
    */

    $submitErrors = $_SESSION['passSubmitErrors'] ?? [];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Reset your password</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" integrity="sha384-dpuaG1suU0eT09tx5plTaGMLBsfDLzUCCUXOY2j/LSvXYuG6Bqs43ALlhIqAJVRb" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../utils/utils.css">

    <!-- Password strength checker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>

    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        function onSubmit(token) {
            document.getElementById("demo-form").submit();
        }
    </script>

</head>

<body class="center">

    <div class="small-bloc">

        <!-- Error indications for failed submissions -->
        <div class="submit-error text-danger ">
            <ul class="small-list">
                <?php
                    // Display error messages according to user mistakes
                    foreach($submitErrors as $errorLabel) {
                        echo "<li class='small-list-item text-danger fw-bold'> * " . $errorLabel . "</li>";
                    }
                ?>
            </ul>
        </div>

        <!-- Title -->
        <div class="horizontal-alignment center space-between">
            <h3>Reset your password</h3>
            <span class="small-font-8 m-0">*Required</span>
        </div>

        <hr>

        <!-- PASSWORD RESET FORM -->
        <form class="vertical-alignment" action="reset_password_action.php" method="POST" id="demo-form">

            <div class="row">

                <!-- Password -->
            <div class="input-bloc vertical-alignment text-light">

                <!-- Password info, checklist -->
                <ul class="small-list" id="pass-checklist">
                    <li class="small-list-item ml-2">At least 12 characters long</li>
                    <li class="small-list-item ml-2">At least one number</li>
                    <li class="small-list-item ml-2">At least one lowercase</li>
                    <li class="small-list-item ml-2">At least one uppercase</li>
                    <li class="small-list-item ml-2">At least one special character</li>
                </ul>

                <!-- Password suggestion -->
                <p class="pass-suggestion invisible">The password is <span id="pass-strength" class="fw-bold"></span><span id="suggestion"></span></p>

                <!-- Password input -->
                <input class="password-input" name="password" type="password" minlength="12" id="password" placeholder="e.g. E8C}n;Nj)_UR7c&" required>
                <div class="space-between">
                    <label class="dark-text-shadow" for="password">Password*</label>
                    <i class="eye-icon bi bi-eye" onclick="togglePassword()"></i>
                    <i class="slashed-eye-icon bi bi-eye-slash hidden" onclick="togglePassword()"></i>
                </div>

                <!-- Script for password indications -->
                <script type="text/javascript" src="../utils/password.js"></script>

            </div>

            <!-- Math captcha -->
            <?php require_once "../scripts/captcha1.php"; ?>

            <!-- Submit + Captcha -->
            <button type="submit" class="g-recaptcha mb-2 blue-pink-gradient btn"
                    <?php
                        require_once "../utils/config.php";
                        echo "data-sitekey=" . $sitekey;
                    ?>
                    data-callback="onSubmit">Create my account
            </button>

            </div>

        </form>

    </div>


</body>