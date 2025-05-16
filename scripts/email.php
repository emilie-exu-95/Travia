<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "../utils/vendor/autoload.php";

function sendEmail($userEmail, $object, $body) {

    global $senderEmail, $senderEmailPassword;
    require "../utils/config.php"; // Config for sender email

    $email = new PHPMailer(true);

    try {

        // Server settings
        $email->isSMTP();
        $email->SMTPAuth = true;
        $email->Host = "mail.gmx.com";
        $email->Port = 587;
        $email->Username = $senderEmail;
        $email->Password = $senderEmailPassword;
        $email->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        // Sender and recipient settings
        $email->setFrom($senderEmail, 'Travia');
        $email->addAddress($userEmail);

        // Content settings
        $email->isHTML(true);
        $email->Subject = $object;
        $email->Body = $body;

        $email->CharSet = 'UTF-8';
        $email->Encoding = 'base64';

        // Send email
        $email->send();

    } catch (Exception $e) {
        // echo "Error while sending mail: " . $e->getMessage();
        echo "An error occurred while trying to send your email. Please try again later.";
    }
}