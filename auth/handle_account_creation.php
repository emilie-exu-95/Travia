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

</head>

<body class="center">

    <div class="small-bloc">

        <h1>Account creation</h1>

        <hr>

        <?php

        session_start();

        // Set default values for form inputs
        $firstname = $_POST['firstname'] ?? "";
        $lastname = $_POST['lastname'] ?? "";
        $email = $_POST['email'] ?? "";
        $passWord = $_POST['password'] ?? "";

        // Optional fields, planets
        $homePlanet = trim($_POST['homePlanet'] ?? "");
        $workPlanet = trim($_POST['workPlanet'] ?? "");

        // Captcha
        $userCaptcha = intval($_POST['captcha1']) ?? "";
        $captchaResult = $_SESSION['captcha_result'] ?? "";

        // Only run file on form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            require_once "../utils/connection.php";
            require_once "../scripts/functions.php"; // validPassword(), getPlanetId()
            require_once "../scripts/email.php"; // sendEmail()

            $submitErrors = []; // Errors to display on form submission/reload for user

            // Validate firstname & lastname -> must only contain letters
            if (!preg_match("/^\p{L}+$/u", $firstname) || !preg_match("/^\p{L}+$/u", $lastname)) {
                $submitErrors[] = "Firstname and Lastname must only contain letters.";
            }

            // Validate email -> required field
            if (empty($email)) {
                $submitErrors[] = "Email is required.";
            }

            // Validate password -> must respect listed criteria
            $validPassword = validPassword($passWord);
            if (!$validPassword) {
                $submitErrors[] = "Password must respect all listed criteria.";
            }

            // Validate home & work planets -> must exist if set by user
            $homeId = ($homePlanet != "") ? getPlanetId($homePlanet) : null;
            $workId = ($workPlanet != "") ? getPlanetId($workPlanet) : null;
            if ($homePlanet !== "" && is_null($homeId)) {
                $submitErrors[] = "Home planet does not exist.";
            }
            if ($workPlanet !== "" && is_null($workId)) {
                $submitErrors[] = "Work planet does not exist.";
            }

            // Validate captcha -> must be correct
            if ($userCaptcha != $captchaResult) {
                $submitErrors[] = "The answer to the math problem is incorrect.";
            }

            // No errors -> create account, else return to form
            if (empty($submitErrors)) {

                try {

                    // Verify account/email existence first
                    $stmt = $dbh->prepare("SELECT id, verified FROM TRAVIA_User WHERE email = :email;");
                    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                    $stmt->execute();
                    $user = $stmt->fetch(PDO::FETCH_OBJ);

                    // User already exists
                    if ($user && $user->verified == 1) {

                        $object = "Welcome to Travia !";
                        $body = "
                            <h1>Hello !</h1>
                            <p>
                                An attempt has been made to create an account with this email
                                but your account already exists.
                                Click the following link to log in :
                                <a href='http://localhost/Travia/login.php'>http://localhost/Travia/login.php</a>
                            </p>
                        ";
                        sendEmail($email, $object, $body);

                        echo "<br>Please check your email to verify your account. Make sure to check your spam folder if you did not receive an email.";

                    } else { // New user, create account and send verification email

                        // Delete existing account if user is not verified
                        if ($user && $user->verified == 0) {

                            // Delete verification token
                            $stmt = $dbh->prepare("DELETE FROM TRAVIA_Verification WHERE user_id = :userId;");
                            $stmt->bindParam(":userId", $user->id, PDO::PARAM_INT);
                            $stmt->execute();

                            // Delete user
                            $stmt = $dbh->prepare("DELETE FROM TRAVIA_User WHERE id = :userId;");
                            $stmt->bindParam(":userId", $user->id, PDO::PARAM_INT);
                            $stmt->execute();
                        }

                        // Prepare a statement for user creation
                        $stmt = $dbh->prepare(
                            "INSERT INTO TRAVIA_User(firstname, lastname, homePlanetId, workPlanetId, email, password)
                        VALUES (:firstname, :lastname, :homeId, :workId, :email, :password);"
                        );

                        // Hash password
                        $hashedPassword = password_hash($passWord, PASSWORD_DEFAULT);

                        // Bind values and execute
                        $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
                        $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
                        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                        $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
                        $stmt->bindParam(':homeId', $homeId, PDO::PARAM_INT);
                        $stmt->bindParam(':workId', $workId, PDO::PARAM_INT);
                        $stmt->execute();

                        // Create a verification token and expiration date
                        $token = bin2hex(random_bytes(16));

                        // Prepare a statement for verification creation
                        $stmt = $dbh->prepare("
                            INSERT INTO TRAVIA_Verification (user_id, token, expiration_date, label)
                            VALUES ((SELECT id FROM TRAVIA_User WHERE email = :email), :token, NOW() + INTERVAL 1 MINUTE, :label);
                        ");

                        // Bind values and execute
                        $stmt->bindParam(':email', $email);
                        $stmt->bindParam(':token', $token);
                        $stmt->bindParam(':label', "Account creation");
                        $stmt->execute();

                        // Send verification email
                        require_once "../scripts/email.php";
                        $object = "Welcome to Travia !";;
                        $body = "
                            <h1>Welcome to Travia !</h1>
                            <p>To complete your registration, please click on the following link :</p>
                            <a href='http://localhost/Travia/auth/verification.php?token=$token'>http://localhost/Travia/auth/verification.php?token=$token</a>
                            <p><small>Be aware : the link expires in 1 minute.</small></p>
                        ";
                        sendEmail($email, $object, $body);

                        // Clear session data on success
                        unset($_SESSION["old"], $_SESSION["submitErrors"]);

                        echo "<br>Please check your email to verify your account. Make sure to check your spam folder if you did not receive an email.";

                    }

                } catch(Exception $e) {
                    echo "Error while creating account : " . $e->getMessage();
                    $submitErrors[] = "An internal error occurred. Please try again later.";
                }

            }

            // Only redirect if there are errors
            if (!empty($submitErrors)) {

                // Store form values and errors for re-display
                $_SESSION["submitErrors"] = $submitErrors;
                $_SESSION["old"] = [
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'email' => $email,
                    'homePlanet' => $homePlanet,
                    'workPlanet' => $workPlanet
                ];

                // Redirect to form
                header("Location: index.php");
                exit;
            }

        }

        ?>

    </div>

</body>

</html>
