<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require "PHPMailer\PHPMailer\PHPMailer.php";
require "PHPMailer\PHPMailer\SMTP.php";
require "PHPMailer\PHPMailer\Exception.php";

//Load Composer's autoloader
//require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                            //Enable verbose debug output
    $mail->isSMTP();                                                 //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                           //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                      //Enable SMTP authentication
    $mail->Username   = 'lionelconcept1@gmail.com';                                       //SMTP username
    $mail->Password   = 'urjkueiajvmybupm ';                                      //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 587;                                   //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('lionelconcept1@gmail.com', 'Sondage SEEG');
    $mail->addAddress('comnkongo@gmail.com', 'Communication Nkongô');     //Add a recipient

    //Attachments
    $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    $mail->addAttachment('/tmp/image.jpg', 'png', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Formulaire de sondage SEEG-POG';
    $mail->Body    =  $commentaires;
    $mail->AltBody = 'Ceci est le corps en texte brut pour les clients de messagerie';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}