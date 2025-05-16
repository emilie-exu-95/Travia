<?php
    session_start();

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


    // Load errors
    $submitErrors = $_SESSION['forgotErrors'] ?? [];
    $email = $_SESSION['old']['email'] ?? "";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Forgot password</title>

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

<body class="center">

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


    <div class="small-bloc">

        <!-- Title -->
        <div class="horizontal-alignment center space-between">
            <h3>Forgot password</h3>
            <span class="small-font-8 m-0">*Required</span>
        </div>

        <hr>

        <!-- FORM -->
        <form action="handle_form_result.php" method="POST" id="demo-form">

            <div class="row">

                <!-- Email -->
                <div class="input-bloc vertical-alignment text-light mb-3">
                    <input required type="email" name="email" id="email" placeholder="jane.doe@gmail.com" value="<?php echo $email; ?>">
                    <label class="dark-text-shadow" for="email">Email*</label>
                </div>

            </div>

            <!-- Math captcha -->
            <?php require_once "../scripts/captcha1.php"; ?>

            <!-- Submit + Captcha -->
            <button type="submit" name="submit!" class="btn g-recaptcha mb-2 blue-pink-gradient w-100"
                <?php
                require_once "../utils/config.php";
                echo "data-sitekey=" . $sitekey;
                ?>
                    data-callback="onSubmit" value="forgot_password">Reset password
            </button>

        </form>


        <hr>

        <p>Remember your password? Log in <a href="../login.php">here</a></p>

    </div>

</body>