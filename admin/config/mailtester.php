<?php
//require_once("../admin/config/db.php");
date_default_timezone_set("America/Tijuana");

//phpmailer smtp emails from admin/packages
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//autoload packages
require 'mail/vendor/autoload.php';



function sendEmail()
{
        

    $recipient = "jgomez@martechmedical.com";
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try 
        {
            
            //Server settings
            $mail->SMTPDebug = 2;                                           // Enable verbose debug output con 2
            $mail->isSMTP(false);                                                // Set mailer to use SMTP
            $mail->Host = '192.168.2.8;192.168.2.8';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = false;                                         // Enable SMTP authentication
            $mail->Username = 'jgomez@martechmedical.com';                  // SMTP username
            $mail->Password = 'joseLuis15!';                             // SMTP password
            $mail->SMTPSecure = 'tls';                                      // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 25;                                              // TCP port to connect to 587
            //antes en 465

$mail->SMTPOptions = array(
                    'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                    )
                );

            //Recipients
            $mail->setFrom('jgomez@martechmedical.com', 'Test');
            
            $mail->addAddress($recipient, 'Responsible');     // Add a recipient
                                
            $mail->addReplyTo('jgomez@martechmedical.com', 'Test');
        

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Test';
            $mail->Body    = 
            "
            <h1>Yay from andon!</h1>

            ";
            $mail->AltBody = 'Your email client does not support html emails.';

            $mail->send();
            echo 'Se envio el mensaje';
        }
        catch (Exception $e) 
        {
            echo 'Message could not be sent. Mailer Error: <br> ', $mail->ErrorInfo;
            
        }
        //EMAILS END
}

sendEmail();
