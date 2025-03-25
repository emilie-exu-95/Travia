<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Create account</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" integrity="sha384-dpuaG1suU0eT09tx5plTaGMLBsfDLzUCCUXOY2j/LSvXYuG6Bqs43ALlhIqAJVRb" crossorigin="anonymous">

    <!-- Font Awesome (icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="index.css">

    <!-- Password strength checker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>

    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        function onSubmit(token) {
            document.getElementById("demo-form").submit();
        }
    </script>

    <!-- FORM INPUTS + INPUT ERRORS -->
    <?php include("handle_creation.php"); ?>

</head>

<body class="center">

    <div class="form-div">

        <!-- Error indications for failed submission -->
        <div class="submit-error text-danger ">
            <ul class="small-list">
                <li class="small-list-item text-danger dark-text-shadow fw-bold">* These fields cannot be empty: First name, Last name, Email, Password.</li>
                <li class="small-list-item text-danger dark-text-shadow fw-bold">* Firstname and Lastname can only contain letters.</li>

                <?php
                    // Display error messages according to user mistakes
                    foreach($submitErrors as $errorCode) {
                        switch($errorCode) {
                            case "emptyFields":
                                echo "<li class='small-list-item text-danger fw-bold'>* These fields cannot be empty: First name, Last name, Email, Password.</li>";
                            case "invalidNames":
                                echo "<li class='small-list-item text-danger fw-bold'>* Firstname can Lastname can only contain letters.</li>";
                            case 2:
                                echo "<li class='small-list-item text-danger fw-bold'>TEXT HERE</li>";
                        }
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
        <form class="vertical-alignment" method="post" action="handle_account_creation.php">

            <!-- FIRST NAME + LAST NAME -->
            <div class="row">

                <!-- First name -->
                <div class="col input-bloc vertical-alignment text-light mb-3">
                    <?php
                        echo "<input name='fname' type='text' id='fname' placeholder='e.g. Jane' value=" . $fname . ">";
                        echo "<label class='dark-text-shadow' for='fname''>First name*</label>";
                    ?>
                </div>

                <!-- Last name -->
                <div class="col input-bloc vertical-alignment text-light mb-3">
                    <?php
                        echo "<input name='lname' type='text' id='lname' placeholder='e.g. Doe' value=" . $lname . ">";
                        echo "<label class='dark-text-shadow' for='lname'>Last name*</label>";
                    ?>
                </div>

            </div>

            <hr>

            <!-- HOME/WORK PLANETS -->
            <div class="row">

                <!-- Home planet -->
                <div class="col input-bloc vertical-alignment text-light mb-3">
                    <input list="planetsList" name="home" type="text" id="home" placeholder="e.g. Alderaan" required>
                    <label class="dark-text-shadow" for="home">Home planet</label>
                </div>

                <!-- Work planet -->
                <div class="col input-bloc vertical-alignment text-light mb-3">
                    <input list="planetsList" name="work" type="text" id="work" placeholder="e.g. Alderaan" required>
                    <label class="dark-text-shadow" for="work">Work planet</label>
                </div>

                <!-- Planets list (data) -->
                <datalist id="planetsList">
                    <!-- Create options wicdth planet names -->
                    <?php
                        include("../utils/connection.php");
                        $result = $dbh->query("SELECT name from TRAVIA_Planet;");
                        while( $line = $result->fetch(PDO::FETCH_OBJ) ) {
                            echo "<option value=" . $line->name . "></option>";
                        }
                    ?>
                </datalist>

            </div>

            <hr>

            <!-- Email -->
            <div class="input-bloc vertical-alignment text-light mb-3">
                <input name="email" type="email" id="email" placeholder="e.g. jane_doe@gmail.com" required>
                <label class="dark-text-shadow" for="email">Email*</label>
            </div>

            <!-- Password -->
            <div class="input-bloc vertical-alignment text-light mb-3">

                <!-- Password info, checklist -->
                <ul class="small-list" id="pass-checklist">
                    <li class="small-list-item ml-2">At least 12 characters long</li>
                    <li class="small-list-item ml-2">At least 1 number</li>
                    <li class="small-list-item ml-2">At least 1 lowercase</li>
                    <li class="small-list-item ml-2">At least 1 uppercase</li>
                    <li class="small-list-item ml-2">At least 1 special character</li>
                </ul>

                <!-- Password suggestion -->
                <p class="pass-suggestion w-80 invisible">The password is <span id="pass-strength" class="fw-bold"></span><span id="suggestion"></span></p>

                <!-- Password input -->
                <input name="pass" type="password" minlength="12" id="pass" placeholder="e.g. E8C}n;Nj)_UR7c'" required>
                <div class="space-between">
                    <label class="dark-text-shadow" for="pass">Password*</label>
                    <i class="fa-solid fa-eye"></i>
                    <i class="fa-solid fa-eye-slash hidden"></i>
                </div>

                <!-- Script for password indications -->
                <script type="text/javascript" src="../utils/password.js"></script>

            </div>

            <!-- Submit + Captcha (local key)-->
            <button type="submit" class="g-recaptcha mb-2 blue-pink-gradient btn"
                    data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"
                    data-callback="onSubmit">Create my account
            </button>

        </form>
    </div>


</body>

<?php include("../utils/footer.php"); ?>

</html>