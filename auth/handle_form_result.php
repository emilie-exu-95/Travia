<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Handle result according to form type
if (isset($_POST['submit'])) {

    require_once "../utils/connection.php";
    require_once "../utils/config.php";

    $form_type = $_POST['submit'];


    switch($form_type) {

        // FORGOT PASSWORD FORM
        case 'forgot_password':

            $email = $_POST['email'] ?? "";
            $userCaptcha = intval($_POST['captcha1']) ?? "";
            $captchaResult = $_SESSION['captcha_result'] ?? "";

            // Email verification
            if (empty($email)) {
                $submitErrors[] = "Please enter your email.";
            }

            // Math captcha
            if ($userCaptcha !== $captchaResult) {
                $submitErrors[] = "The answer to the math problem is incorrect.";

                // Store form value
                $_SESSION['forgotErrors'] = $submitErrors;
                $_SESSION['old'] = [
                    'email' => $email
                ];

                // Redirect to form
                header('Location: forgot_password.php');
            }

            // No errors -> send email to reset password
            if (empty($submitErrors)) {

                // Create verification token
                $token = bin2hex(random_bytes(16));

                // Prepare a statement for verification
                $stmt = $dbh->prepare(
                    "INSERT INTO TRAVIA_Verification (user_id, token, expiration_date, label)
                VALUES ((SELECT id FROM TRAVIA_User WHERE email = :email), :token, NOW() + INTERVAL 1 MINUTE, :label);        "
                );

                // Bind values and execute
                $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                $stmt->bindParam(":token", $token, PDO::PARAM_STR);
                $stmt->bindParam(":label", "Forgot password", PDO::PARAM_STR);
                $stmt->execute();

                // Send email to reset password
                $object = "Travia - Reset password";
                $body = "
                    <h1>Reset your password</h1>;
                    <p>You have requested to reset your password. Click on the following link to create a new password:</p>
                    <a href='http://localhost/Travia/auth/reset_password.php?token=$token'>Reset password</a>
                    <p><small>Be aware : the link expires in 1 minute.</small></p>
                    
                    <p>If you did not request a password reset, you can ignore this email. Your password will not be changed.</p>
                ";
                sendEmail($email, $object, $body);;

            } else {
                header('Location: forgot_password.php');
            }
            break;

        // LOGIN FORM
        case 'login':
            break;

        // DEFAULT, Redirect to login page
        default:
            header('Location: ../index.php');
    }
} else {
    header('Location: ../index.php');
}

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
    <link rel="stylesheet" href="../utils/utils.css">

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

<body class='center'>

    <div class='small-bloc'>

        <!-- Title -->
        <div class="horizontal-alignment center space-between">
            <?php
            switch($form_type) {
                case 'forgot_password':
                    echo '<h3>Forgot password</h3>';
                    break;
                case 'login':
                    echo '<h3>Log in</h3>';
                    break;
            }
            ?>
        </div>

        <hr>

        <?php
        switch($form_type) {

            case 'forgot_password':
                echo 'If your account exists, you will receive an email with a link to reset your password. If you do not receive an email, please check your spam folder.';
                break;

            case 'login':
                $_SESSION['user_id'] = "user_id";
        }

        ?>

    </div>

</body>

