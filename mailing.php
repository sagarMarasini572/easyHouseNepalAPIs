<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// including required files
include './PHPMailer-master/AutoLoad.php';
include './PHPMailer-master/src/Exception.php';
include './PHPMailer-master/src/PHPMailer.php';
include './PHPMailer-master/src/SMTP.php';

//  function to send email
function sendMail($subject, $email, $name, $code)
{
    // creating an object of PHPMailer
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'sagar-marasini.com.np';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'easyhousenepal@sagar-marasini.com.np';                     //SMTP username
        $mail->Password   = '9s~x25z2?;^#';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        
        //Recipients
        $mail->setFrom('easyhousenepal@sagar-marasini.com.np', 'easyHouse Nepal');
        $mail->addAddress($email, $name);     //Add a recipient

        //Content
        $mail->isHTML(true);
        //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $code;
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
    } catch (Exception $e) {
        // throwing error message
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
 
function sendContactMail($email, $name, $message)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'sagar-marasini.com.np';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'easyhousenepal@sagar-marasini.com.np';                     //SMTP username
        $mail->Password   = '9s~x25z2?;^#';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($email, $name);
        $mail->addAddress('easyhousenepal@sagar-marasini.com.np', 'easyHouse Nepal');     //Add a recipient

        //Content
        $mail->isHTML(true);
        //Set email format to HTML
        $mail->Subject = 'Message from users';
        $mail->Body    = $message;
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
