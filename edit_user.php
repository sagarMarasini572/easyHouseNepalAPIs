<?php

header("Access-Control-Allow-Origin: *");
// include '../DatabaseConfig.php'
include './DatabaseConfig.php';
// Create connection
$connect = mysqli_connect($HostName, $HostUser, $HostPass, $DatabaseName);

// check user input
if (
    isset($_POST['userId']) &&
    isset($_POST['email']) &&
    isset($_POST['full_name']) &&
    isset($_POST['contact'])
) {
    // storing user input
    $userId = $_POST['userId'];
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $contact = $_POST['contact'];
    // Create PHP Code to update user details in the database.
    $updateDetails = "UPDATE users set full_name='$full_name', email='$email', contact='$contact' where user_id='$userId'";
    // if query is executed successfully
    if ($result = mysqli_query($connect, $updateDetails)) {
        // defining the message to be displayed
        $data = ['success' => true, 'message' => ['User Sucessfully updated!']];
    } else {
        // defining the message to be displayed
        $data = ['success' => false, 'message' => ['Something went wrong!']];
    }
    // encoding the data in json format.
    echo json_encode($data);
} else {
    // showing error message
    $data = ['success' => false, 'message' => ['All the fields are required!']];
    // echoing json data
    echo json_encode($data);
}
