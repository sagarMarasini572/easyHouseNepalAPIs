<?php

header("Access-Control-Allow-Origin: *");
// include '../DatabaseConfig.php'
include './DatabaseConfig.php';
// Create connection
$connect = mysqli_connect($HostName, $HostUser, $HostPass, $DatabaseName);
// checking for password, email and code
if (isset($_POST['password']) && isset($_POST['code']) && isset($_POST['email'])) {
    // encrypting the password
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    // storing user input
    $code = $_POST['code'];
    $email = $_POST['email'];
    // Create PHP Code to get the user details from the database where the email and password reset code matches the email provided by the user.
    $sql = "SELECT * FROM users where email='$email' and password_reset_code='$code'";
    // Executing SQL Query.
    $result = mysqli_query($connect, $sql);
    // if the user exists
    if ($result->num_rows > 0) {
        // Create PHP Code to update the user password in the database.
        $sql = "UPDATE users SET password='$password', password_reset_code=null where email='$email'";
        // if query is successful
        if (mysqli_query($connect, $sql)) {
            // showing success message
            $data = ['success' => true, 'message' => ['Password updated successfully!']];
        } else {
            // showing failure message
            $data = ['success' => false, 'message' => ['Something went wrong!']];
        }
    } else {
        // showing failure message
        $data = ['success' => false, 'message' => ['Invalid code!']];
    }
    // encoding the data in json format.
    echo json_encode($data);
} else {
    // showing failure message
    $data = ['success' => false, 'message' => ['All the fields are required!']];
    // encoding the data in json format.
    echo json_encode($data);
}
