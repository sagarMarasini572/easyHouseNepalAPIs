<?php

header("Access-Control-Allow-Origin: *");
// include '../DatabaseConfig.php'
include './DatabaseConfig.php';
// Create connection
$connect = mysqli_connect($HostName, $HostUser, $HostPass, $DatabaseName);
// checking user id and otp
if (isset($_POST['user_id']) && isset($_POST['otp'])) {
    // storing user input
    $user_id = $_POST['user_id'];
    $otp = $_POST['otp'];

    // Create PHP Code to get the user details from the database where user id matches.
    $sql = "SELECT * FROM users where user_id='$user_id'";
    // Executing SQL Query.
    $result = mysqli_query($connect, $sql);
    // if user id matches
    if ($row = mysqli_fetch_array($result)) {
        // if otp matches
        if ($row['verification_code'] == $otp) {
            // Create PHP Code to update users verification code.
            $sql = "UPDATE users SET verification_code=null where user_id='$user_id'";
            // if query is successful
            if (mysqli_query($connect, $sql)) {
                // showing success message
                $data = ['success' => true, 'message' => ['OTP verified successfully!']];
            } else {
                // showing failure message
                $data = ['success' => false, 'message' => ['Something went wrong!']];
            }
        // else if verification code is null
        } else if ($row['verification_code'] == null) {
            // showing failure message
            $data = ['success' => true, 'message' => ['User already verified!']];
        // else if verification code does not match
        } else {
            // showing failure message
            $data = ['success' => false, 'message' => ['Invalid OTP!']];
        }
    } else {
        // showing failure message
        $data = ['success' => false, 'message' => ['Invalid OTP!']];
    }
    // encoding the data in json format.
    echo json_encode($data);
} else {
    // showing failure message
    $data = ['success' => false, 'message' => ['All the fields are required!']];
    // encoding the data in json format.
    echo json_encode($data);
}
