<?php

global $dbh;

// Retrieve input
$input = json_decode(file_get_contents('php://input'), true);

// Data to send as json, default: no connexion
$result = [
    'connexion' => false
];

if (isset($input['login']) && isset($input['hashed_password']) && isset($input['token']) {

    require_once '../utils/connection.php';

    $login = $input['login']; // email
    $hashed_password = $input['hashed_password'];
    $token = $input['token'];

    // Retreive information from database
    $stmt = $dbh->prepare("SELECT id, password, verified, date_time, token FROM TRAVIA_USER WHERE email = :login AND token = :token AND password = :password AND verified = 1;");
    $stmt->bindParam(':login', $login, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_OBJ);

    if ($user) {

        // Store data
        $result = [
            'connexion' => true,
            'user_id' => $user->id,
            'verified_account ' => true,
            'creation_time' => $user->date_time,
            'token' => $user->token,
        ];

    }

}

// Output : json
echo json_encode($result);