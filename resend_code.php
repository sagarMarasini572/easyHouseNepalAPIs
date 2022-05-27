<?php
header("Access-Control-Allow-Origin: *");
// including required files
include './mailing.php';
include './generateOTP.php';
include './DatabaseConfig.php';
// Create connection
$connect = mysqli_connect($HostName, $HostUser, $HostPass, $DatabaseName);
// check user input
if (isset($_POST['email']) && isset($_POST['type'])) {
    // storing user input
    $email = $_POST['email'];
    $type = $_POST['type'];
    $otp = generateOTP();
    // calling the function to send the email
    sendMail('OTP ', 'gadeshsharma00572@gmail.com', 'Gadesh Sharma', $otp);
    // if the user is a new user
    if ($type == 'verify') {
        // Create PHP Code to update verification code in the database where email matches.
        $sql = "UPDATE users SET verification_code='$otp' where email='$email'";
    } else {
        // Create PHP Code to update password reset code  in the database where email matches.
        $sql = "UPDATE users SET password_reset_code='$otp' where email='$email'";
    }
    // if query is executed successfully
    if (mysqli_query($connect, $sql)) {
        // defining the message to be displayed
        $data = ['success' => true, 'message' => ['OTP sent successfully!']];
    } else {
        //  defining the message to be displayed
        $data = ['success' => false, 'message' => ['Something went wrong!']];
    }
    // encoding the data in json format.
    echo json_encode($data);
} else {
    // showing error message
    $data = ['success' => false, 'message' => ['All the fields are required!']];
    // encoding the data in json format.
    echo json_encode($data);
}
