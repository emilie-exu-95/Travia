<?php
    session_start();
    /*
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    */

    // Load submitted values from session
    $submitErrors = $_SESSION['submitErrors'] ?? [];

    // Set input fields from previous inputs
    $firstname = htmlspecialchars($_SESSION['old']['firstname'] ?? "");
    $lastname = htmlspecialchars($_SESSION['old']['lastname'] ?? "");
    $email = htmlspecialchars($_SESSION['old']['email'] ?? "");
    $homePlanet = htmlspecialchars($_SESSION['old']['homePlanet'] ?? "");
    $workPlanet = htmlspecialchars($_SESSION['old']['workPlanet'] ?? "");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Create account</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" integrity="sha384-dpuaG1suU0eT09tx5plTaGMLBsfDLzUCCUXOY2j/LSvXYuG6Bqs43ALlhIqAJVRb" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../utils/utils.css">

    <!-- Font Awesome (icons), no longer working -->
    <!-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet"> -->

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
            <h3>Create an account</h3>
            <span class="small-font-8 m-0">*Required</span>
        </div>

        <hr>

        <!-- FORM -->
        <form id="demo-form" class="vertical-alignment" method="post" action="handle_account_creation.php">

            <!-- FIRST NAME + LAST NAME -->
            <div class="row">

                <!-- First name -->
                <div class="col input-bloc vertical-alignment text-light mb-3">
                    <input name="firstname" type="text" id="firstname" placeholder="e.g. Jane" value="<?php echo $firstname; ?>" />
                    <label class="dark-text-shadow" for="firstname">First name*</label>
                </div>

                <!-- Last name -->
                <div class="col input-bloc vertical-alignment text-light mb-3">
                    <input name="lastname" type="text" id="lastname" placeholder="e.g. Doe" value="<?php echo $lastname; ?>" />
                    <label class="dark-text-shadow" for="lastname">Last name*</label>
                </div>

            </div>

            <hr>

            <!-- HOME/WORK PLANETS -->
            <div class="row">

                <!-- Planets list (data) -->
                <datalist id="planetsList">
                    <!-- Create options with planet names -->
                    <?php
                    try {
                        require "../utils/connection.php";
                        $result = $dbh->query("SELECT name from TRAVIA_Planet order by name;");
                        echo $result->rowCount() . " planets found.";
                        while( $line = $result->fetch(PDO::FETCH_OBJ) ) {
                            echo '<option value="' . htmlspecialchars($line->name) . '"></option>';
                        }
                    } catch (Exception $e) {
                        echo "Error while loading planets list : " . $e->getMessage();
                    }
                    ?>
                </datalist>

                <!-- Home planet -->
                <div class="col input-bloc vertical-alignment text-light mb-3">
                    <input list="planetsList" name="homePlanet" type="text" id="homePlanet" placeholder="e.g. Alderaan" value="<?php echo $homePlanet; ?>" />
                    <label class="dark-text-shadow" for="homePlanet">Home planet</label>
                </div>

                <!-- Work planet -->
                <div class="col input-bloc vertical-alignment text-light mb-3">
                    <input list="planetsList" name="workPlanet" type="text" id="workPlanet" placeholder="e.g. Geonosis" value="<?php echo $workPlanet; ?>" />
                    <label class="dark-text-shadow" for="workPlanet">Work planet</label>
                </div>

            </div>

            <hr>

            <!-- Email -->
            <div class="input-bloc vertical-alignment text-light mb-3">
                <input type="email" required name="email" id="email" placeholder="jane.doe@gmail.com" value="<?php echo $email; ?>">
                <label class="dark-text-shadow" for="email">Email*</label>
            </div>


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

        </form>

        <hr>

        <p>Already have an account? Login <a href="../login.php">here</a></p>
    </div>


</body>

<?php include("../utils/footer.php"); ?>

</html>