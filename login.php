<?php
    session_start();
    /*
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    */

    // Load errors
    $submitErrors = $_SESSION['loginErrors'] ?? [];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login</title>

    <!-- Styles -->
    <link rel="icon" type="image/x-icon" href="">
    <link rel="stylesheet" href="utils/utils.css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" integrity="sha384-dpuaG1suU0eT09tx5plTaGMLBsfDLzUCCUXOY2j/LSvXYuG6Bqs43ALlhIqAJVRb" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        function onSubmit(token) {
            document.getElementById("demo-form").submit();
        }
    </script>

</head>

<?php require_once "utils/config.php"; ?>

<body class="center">

    <div class="small-bloc">

        <!-- Error indications for failed submission -->
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
            <h3>Log in</h3>
            <span class="small-font-8 m-0">*Required</span>
        </div>

        <hr>

        <!-- LOGIN FORM -->
        <form class="vertical-alignment" method="post" action="login_code.php">

            <div class="row mb-3">

                <!-- Email -->
                <div class="col input-bloc vertical-alignment text-light mb-3">
                    <input name='email' type='text' id='email' placeholder='e.g. jane.doe@gmail.com' required/>
                    <label class='dark-text-shadow' for='email'>Email*</label>
                </div>

                <!-- Password -->
                <div class="input-bloc vertical-alignment text-light">

                    <!-- Password input -->
                    <input class="password-input" name="password" type="password" minlength="12" id="password" placeholder="e.g. E8C}n;Nj)_UR7c&" required>
                    <div class="space-between">
                        <label class="dark-text-shadow" for="password">Password*</label>
                        <i class="eye-icon bi bi-eye" onclick="togglePassword()"></i>
                        <i class="slashed-eye-icon bi bi-eye-slash hidden" onclick="togglePassword()"></i>
                    </div>

                    <!-- Script for password indications -->
                    <script type="text/javascript" src="utils/password.js"></script>

                </div>

                <!-- Forgot Password -->
                <div class="w-100 text-light d-flex justify-content-end mt-2">
                    <a class="small-a" href="auth/forgot_password.php">Forgot password?</a>
                </div>

            </div>


            <!-- Math Captcha -->
            <?php include("scripts/captcha1.php") ?>

            <!-- Submit + Captcha (local key)-->
            <button type="submit" class="g-recaptcha mb-2 blue-pink-gradient btn"
                    <?php
                        require_once "utils/config.php";
                        echo "data-sitekey=" . $sitekey;
                    ?>
                    data-callback="onSubmit">Login
            </button>

        </form>

        <hr>

        <p>Don't have an account yet? Sign in <a href="auth/index.php">here</a></p>

    </div>

</body>

